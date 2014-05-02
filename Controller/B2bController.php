<?php

namespace Wk\DhlApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;

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
     * Book pickup action
     *
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
     *
     * @param Request $request
     * @return Response
     */
    public function bookPickupAction(Request $request)
    {
        $pickupRequest = $this->serializer->deserialize(
            $request->getContent(),
            'Wk\DhlApiBundle\Model\B2b\Request\BookPickupRequest',
            'json'
        );

        $result = $this->connection->bookPickup(
            $pickupRequest->BookingInformation,
            $pickupRequest->PickupAddress,
            $pickupRequest->ContactOrderer
        );

        return $this->generateResponse($result);
    }

    /**
     * Cancel pickup action
     *
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
    public function cancelPickupAction(Request $request)
    {
        $result = $this->connection->cancelPickup($request->query->get('id'));

        return $this->generateResponse($result);
    }

    /**
     * Create shipment action (day definite)
     *
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
        $shipmentOrder = $this->serializer->deserialize(
            $request->getContent(),
            'Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType',
            'json'
        );
        print_r($shipmentOrder);
        print_r($this->serializer->serialize($shipmentOrder, 'xml'));

        $result = $this->connection->createShipmentDD($shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * delete shipment action (day definite)
     *
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
     * Update shipment action (day definite)
     *
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
        $shipmentOrder  = $this->serializer->deserialize(
            $request->getContent(),
            'Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType',
            'json'
        );

        $result = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * Get label action (day definite)
     *
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
     * Get export document action (day definite)
     *
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
     * Create shipment action (time definite)
     *
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
        $shipmentOrder = $this->serializer->deserialize(
            $request->getContent(),
            'Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType',
            'json'
        );

        $result = $this->connection->createShipmentTD($shipmentOrder);

        return $this->generateResponse($result);
    }

    /**
     * Delete shipment action (time definite)
     *
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
     * Get label action (time definite)
     *
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
     * Get export document action (time definite)
     *
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
}
