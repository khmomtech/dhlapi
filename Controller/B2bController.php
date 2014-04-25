<?php

namespace Wk\DhlApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Wk\DhlApiBundle\Controller\SerializerController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wk\DhlApiBundle\Lib\B2b\BankType;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Lib\B2b\DeliveryOnTime;
use Wk\DhlApiBundle\Lib\B2b\ExportDocumentDDType;
use Wk\DhlApiBundle\Lib\B2b\FurtherAddressesDDType;
use Wk\DhlApiBundle\Lib\B2b\Ident;
use Wk\DhlApiBundle\Lib\B2b\IdentityType;
use Wk\DhlApiBundle\Lib\B2b\Pickup;
use Wk\DhlApiBundle\Lib\B2b\PickupDetailsType;
use Wk\DhlApiBundle\Lib\B2b\ReceiverDDType;
use Wk\DhlApiBundle\Lib\B2b\ServiceGroupDateTimeOptionDDType;
use Wk\DhlApiBundle\Lib\B2b\Shipment;
use Wk\DhlApiBundle\Lib\B2b\ShipmentDetailsDDType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentDetailsTDType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentItemDDType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentNotificationType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentOrderDDType;
use Wk\DhlApiBundle\Lib\B2b\CommunicationType;
use Wk\DhlApiBundle\Lib\B2b\Company;
use Wk\DhlApiBundle\Lib\B2b\CountryType;
use Wk\DhlApiBundle\Lib\B2b\NameType;
use Wk\DhlApiBundle\Lib\B2b\NativeAddressType;
use Wk\DhlApiBundle\Lib\B2b\Person;
use Wk\DhlApiBundle\Lib\B2b\PickupAddressType;
use Wk\DhlApiBundle\Lib\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Lib\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentOrderTDType;
use Wk\DhlApiBundle\Lib\B2b\ShipmentServiceDDType;
use Wk\DhlApiBundle\Lib\B2b\ServiceGroupIdentDDType;
use Wk\DhlApiBundle\Lib\B2b\ShipperDDType;
use Wk\DhlApiBundle\Lib\B2b\ZipType;

/**
 * Class B2bController
 * @package Wk\DhlApiBundle\Controller
 * @Route ("/b2b")
 */
class B2bController extends SerializerController
{
    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        if(is_null($container)) {
            $this->connection = null;
        } else {
            $this->connection = $this->get('wk_dhl_api.b2b.connection');
        }
    }

    /**
     * @Method ("POST")
     * @Route (
     *      "/pickup.{_format}",
     *      name="wk_dhl_api_b2b_book_pickup",
     *      requirements={
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     * @ParamConverter("", class="WkDhlApiBundle:PickupBookingInformationType")
     *
     * @param PickupBookingInformationType $information
     * @return Response
     * @throws HttpException
     */
    public function bookPickupAction(PickupBookingInformationType $information)
    {
        $ordererName = new NameType(
            new Person(
                $request->request->get('orderer.person.salutation'),
                $request->request->get('orderer.person.title'),
                $request->request->get('orderer.person.firstname'),
                $request->request->get('orderer.person.middlename'),
                $request->request->get('orderer.person.lastname')
            ),
            new Company(
                $request->request->get('orderer.company.name'),
                $request->request->get('orderer.company.addition')
            )
        );

        $ordererAddress = new NativeAddressType(
            $request->request->get('orderer.street.name'),
            $request->request->get('orderer.street.number'),
            $request->request->get('orderer.care_of_name'),
            new ZipType($request->request->get('orderer.zip')),
            $request->request->get('orderer.city'),
            $request->request->get('orderer.district'),
            new CountryType(
                $request->request->get('orderer.country.name'),
                $request->request->get('orderer.country.code'),
                $request->request->get('orderer.state')
            ),
            $request->request->get('orderer.floor'),
            $request->request->get('orderer.room'),
            $request->request->get('orderer.country.code'),
            $request->request->get('orderer.note')
        );

        $ordererCommunication = new CommunicationType(
            $request->request->get('orderer.communication.phone'),
            $request->request->get('orderer.communication.ail'),
            $request->request->get('orderer.communication.fax'),
            $request->request->get('orderer.communication.mobile'),
            $request->request->get('orderer.communication.internet'),
            $request->request->get('orderer.communication.contact')
        );

        $bookingInformation =  new PickupBookingInformationType(
            $request->request->get('pickup.product'),
            $this->container->getParameter('wk_dhl_api.b2b.ekp'),
            $this->container->getParameter('wk_dhl_api.b2b.attendance'),
            strftime('%Y-%m-%d',    $request->request->get('pickup.time.start')),
            strftime('%H:%M',       $request->request->get('pickup.time.start')),
            strftime('%H:%M',       $request->request->get('pickup.time.end')),
            $request->request->get('pickup.remark'),
            $request->request->get('pickup.location'),
            $request->request->get('pickup.amount.pieces'),
            $request->request->get('pickup.amount.pallets'),
            $request->request->get('pickup.weight.piece'),
            $request->request->get('pickup.count'),
            $request->request->get('pickup.weight.total'),
            $request->request->get('pickup.max.length'),
            $request->request->get('pickup.max.width'),
            $request->request->get('pickup.max.height')
        );

        $address = $this->getPickupAddressTypeFromRequest($request);
        $orderer = new PickupOrdererType(
            $ordererName,
            $request->request->get('orderer.name'),
            $ordererAddress,
            $ordererCommunication
        );

        $result = $this->connection->bookPickup($bookingInformation, $address, $orderer);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("DELETE")
     * @Route (
     *      "/pickup/{id}.{_format}",
     *      name="wk_dhl_api_b2b_cancel_pickup",
     *      requirements={
     *          "id"="\d+",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function  cancelPickupAction(Request $request)
    {
        $result = $this->connection->cancelPickup($request->query->get('id'));

        return $this->generateResponse($result);
    }

    /**
     * @Method ("POST")
     * @Route (
     *      "/dd/shipment.{_format}",
     *      name="wk_dhl_api_b2b_create_shipment_dd",
     *      requirements={
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function createShipmentDDAction(Request $request)
    {
        $shipmentOrder = $this->getShipmentOrderDDTypeFromRequest($request);
        $result = $this->connection->createShipmentDD($shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("DELETE")
     * @Route (
     *      "/dd/shipment/{id}.{_format}",
     *      name="wk_dhl_api_b2b_delete_shipment_dd",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function deleteShipmentDDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->deleteShipmentDD($shipmentNumber);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("PUT")
     * @Route (
     *      "/dd/shipment/{id}.{_format}",
     *      name="wk_dhl_api_b2b_update_shipment_dd",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function updateShipmentDDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $shipmentOrder  = $this->getShipmentOrderDDTypeFromRequest($request);
        $result = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("GET")
     * @Route (
     *      "/dd/label/{id}.{_format}",
     *      name="wk_dhl_api_b2b_get_label_dd",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function getLabelDDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->getLabelDD($shipmentNumber);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("GET")
     * @Route (
     *      "/dd/export/{id}.{_format}",
     *      name="wk_dhl_api_b2b_get_export_dd",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json|pdf)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function getExportDocDDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->getExportDocDD($shipmentNumber, 'PDF');

        if($request->query->get('_format') == 'pdf') {
            return new Response(
                base64_decode($result->ExportDocData->ExportDocPDFData),
                Response::HTTP_OK,
                array('content-type' => 'application/pdf')
            );
        }

        return $this->generateResponse($result);
    }

    /**
     * @Method ("POST")
     * @Route (
     *      "/td/shipment.{_format}",
     *      name="wk_dhl_api_b2b_create_shipment_td",
     *      requirements={
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function createShipmentTDAction(Request $request)
    {
        $shipmentOrder = $this->getShipmentOrderTDTypeFromRequest($request);
        $result = $this->connection->createShipmentTD($shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("DELETE")
     * @Route (
     *      "/td/shipment/{id}.{_format}",
     *      name="wk_dhl_api_b2b_delete_shipment_td",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function deleteShipmentTDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->deleteShipmentTD($shipmentNumber);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("GET")
     * @Route (
     *      "/td/label/{id}.{_format}",
     *      name="wk_dhl_api_b2b_get_label_td",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function getLabelTDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->getLabelTD($shipmentNumber);

        return $this->generateResponse($result);
    }

    /**
     * @Method ("GET")
     * @Route (
     *      "/td/export/{id}.{_format}",
     *      name="wk_dhl_api_b2b_get_export_td",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json|pdf)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function getExportDocTDAction(Request $request)
    {
        $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
        $result = $this->connection->getExportDocTD($shipmentNumber, 'PDF');

        if($request->query->get('_format') == 'pdf') {
            return new Response(
                base64_decode($result->ExportDocData->ExportDocPDFData),
                Response::HTTP_OK,
                array('content-type' => 'application/pdf')
            );
        }

        return $this->generateResponse($result);
    }

    /**
     * Helper to create the required objects by request data
     *
     * @param Request $request
     * @return ShipmentOrderDDType
     */
    private function getShipmentOrderDDTypeFromRequest(Request $request)
    {
        if (!$request->request->has('order')) {
            return null;
        }

        $shipment = new Shipment(
            $this->getShipmentDetailsDDTypeFromRequest($request),
            new ShipperDDType(
                new NameType()

            ),
            new ReceiverDDType(),
            $this->getExportDocumentDDTypeFromRequest($request),
            new IdentityType(),
            new FurtherAddressesDDType()
        );

        return new ShipmentOrderDDType(
            $request->request->get('order.sequence_number'),
            $shipment,
            $this->getPickupFromRequest($request),
            'XML',
            (bool)$request->request->get('order.only_if_codeable')
        );
    }

    /**
     * Helper to create an international shipment order by request data
     *
     * @param Request $request
     * @return ShipmentOrderTDType
     */
    private function getShipmentOrderTDTypeFromRequest(Request $request)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * Helper to create a bank type object by request data
     *
     * @param Request $request
     * @return null|BankType
     */
    private function getBankTypeFromRequest(Request $request)
    {
        if(!$request->request->has('bank')) {
            return null;
        }

        return new BankType(
            $request->request->get('bank.account.owner'),
            $request->request->get('bank.account.number'),
            $request->request->get('bank.code'),
            $request->request->get('bank.name'),
            $request->request->get('bank.iban'),
            $request->request->get('bank.note'),
            $request->request->get('bank.bic')
        );
    }

    /**
     * Helper to create a notification object by request data
     *
     * @param Request $request
     * @return null|ShipmentNotificationType
     */
    private function getShipmentNotificationTypeByRequest(Request $request)
    {
        if(!$request->request->has('notification')) {
            return null;
        }

        return new ShipmentNotificationType(
            $request->request->get('notification.recipient.name'),
            $request->request->get('notification.recipient.email')
        );
    }

    /**
     * Helper to create a shipment item object by request data
     *
     * @param Request $request
     * @return null|ShipmentItemDDType
     */
    private  function getShipmentItemDDTypeFromRequest(Request $request)
    {
        if(!$request->request->has('item')) {
            return null;
        }

        return new ShipmentItemDDType(
            $request->request->get('item.weight'),
            $request->request->get('item.length'),
            $request->request->get('item.width'),
            $request->request->get('item.height'),
            $request->request->get('item.package_type')
        );
    }

    /**
     * Helper to create a shipment service object by request data
     *
     * @param Request $request
     * @return null|ShipmentServiceDDType
     */
    private function getShipmentServiceDDTypeFromRequest(Request $request)
    {
        if(!$request->request->has('service')) {
            return null;
        }

        return new ShipmentServiceDDType(
            $this->getServiceGroupDateTimeOptionDDTypeFromRequest($request),
            $this->getServiceGroupIdentDDTypeFromRequest($request),
            $this->getServiceGroupPickupDDTypeFromRequest($request),
            $this->getServiceGroupBusinessPackInternationalDDTypeFromRequest($request),
            $this->getServiceGroupDHLPaketDDTypeFromRequest($request),
            $this->getServiceGroupOtherDDTypeFromRequest($request)
        );
    }

    /**
     * Helper to create a service date time option object by request data
     *
     * @param Request $request
     * @return null|ServiceGroupDateTimeOptionDDType
     */
    private function getServiceGroupDateTimeOptionDDTypeFromRequest(Request $request)
    {
        if(!$request->request->has('service.date_time')) {
            return null;
        }

        if($request->request->has('service.date_time.delivery_on_time')) {
            $deliveryOnTime = new DeliveryOnTime($request->request->get('service.date_time'));
        } else {
            $deliveryOnTime = null;
        }

        return new ServiceGroupDateTimeOptionDDType(
            $deliveryOnTime,
            $request->request->has('service.date_time.delivery_early'),
            $request->request->has('service.date_time.express_0900'),
            $request->request->has('service.date_time.express_1000'),
            $request->request->has('service.date_time.express_1200'),
            $request->request->has('service.date_time.delivery_afternoon'),
            $request->request->has('service.date_time.delivery_evening'),
            $request->request->has('service.date_time.express_saturday'),
            $request->request->has('service.date_time.express_sunday')
        );
    }

    /**
     * Helper to create a shipment details object by request data
     *
     * @param Request $request
     * @return null|ShipmentDetailsDDType
     */
    private function getShipmentDetailsDDTypeFromRequest(Request $request)
    {
        if(!$request->request->has('shipment')) {
            return null;
        }

        return new ShipmentDetailsDDType(
            $this->container->getParameter('wk_dhl_api.b2b.ekp'),
            $this->container->getParameter('wk_dhl_api.b2b.attendance'),
            $request->request->get('shipment.reference'),
            $request->request->get('shipment.description'),
            $request->request->get('shipment.remarks'),
            $this->getShipmentItemDDTypeFromRequest($request),
            $this->getShipmentServiceDDTypeFromRequest($request),
            $this->getShipmentNotificationTypeByRequest($request),
            $request->request->get('notification.text'),
            $this->getBankTypeFromRequest($request)
        );
    }

    /**
     * Helper to create a pickup object by request data
     *
     * @param Request $request
     * @return null|Pickup
     */
    private function getPickupFromRequest(Request $request)
    {
        if(!$request->request->has('pickup')) {
            return null;
        }

        $pickupDetails = new PickupDetailsType(
            strftime('%Y-%m-%d',    $request->request->get('pickup.time.start')),
            strftime('%H:%M',       $request->request->get('pickup.time.start')),
            strftime('%H:%M',       $request->request->get('pickup.time.end')),
            $request->request->get('pickup.remark'),
            $request->request->get('pickup.location'),
            $request->request->get('pickup.amount.pieces'),
            $request->request->get('pickup.amount.pallets'),
            $request->request->get('pickup.amount.pallets'),
            $request->request->get('pickup.weight.piece'),
            $request->request->get('pickup.count'),
            $request->request->get('pickup.weight.total'),
            $request->request->get('pickup.max.length'),
            $request->request->get('pickup.max.width'),
            $request->request->get('pickup.max.height')
        );

        return new Pickup(
            $pickupDetails,
            $this->getPickupAddressTypeFromRequest($request)
        );
    }

    /**
     * Helper to create a pickup address object by request data
     *
     * @param Request $request
     * @return null|PickupAddressType
     */
    private function getPickupAddressTypeFromRequest(Request $request)
    {
        if (!$request->request->has('address')) {
            throw new HttpException('400', 'no address found');
        }

        $person = new Person(
            $request->request->get('address.person.salutation'),
            $request->request->get('address.person.title'),
            $request->request->get('address.person.name.first'),
            $request->request->get('address.person.name.middle'),
            $request->request->get('address.person.name.last')
        );

        $company = new Company(
            $request->request->get('address.company.name'),
            $request->request->get('address.company.addition')
        );

        $communication = new CommunicationType(
            $request->request->get('communication.phone'),
            $request->request->get('communication.email'),
            $request->request->get('communication.fax'),
            $request->request->get('communication.mobile'),
            $request->request->get('communication.internet'),
            $request->request->get('communication.contact')
        );

        $name    = new NameType($person, $company);
        $address = $this->getNativeAddressTypeFromRequest($request);

        return new PickupAddressType($name, $address, $communication);
    }

    /**
     * Helper to create an address object by request data
     *
     * @param Request $request
     * @return null|NativeAddressType
     */
    private function getNativeAddressTypeFromRequest(Request $request)
    {
        if (!$request->request->has('address')) {
            return null;
        }

        return new NativeAddressType(
            $request->request->get('address.street.name'),
            $request->request->get('address.street.number'),
            $request->request->get('address.care_of_name'),
            new ZipType($request->request->get('address.zip')),
            $request->request->get('address.city'),
            $request->request->get('address.district'),
            new CountryType(
                $request->request->get('address.country.name'),
                $request->request->get('address.country.code'),
                $request->request->get('address.country.state')
            ),
            $request->request->get('address.floor'),
            $request->request->get('address.room'),
            $request->request->get('address.country.code'),
            $request->request->get('address.note')
        );
    }

    private function getExportDocumentDDTypeFromRequest(Request $request)
    {
        if (!$request->request->has('export')) {
            return null;
        }

        return new ExportDocumentDDType(
        );
    }
}
