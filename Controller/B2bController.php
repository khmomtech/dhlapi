<?php

namespace Wk\DhlApiBundle\Controller;

use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wk\DhlApiBundle\Lib\B2b\IdentCode;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\Response\IdentCodeResponse;
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
     * Returns an ident code of the given type (see config)
     *
     * @Method ("GET")
     * @Route (
     *      "/identcode/{account}/{id}.{_format}",
     *      name="wk_dhl_api_b2b_ident_code",
     *      requirements={
     *          "id"="\d+",
     *          "account"="\w+",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     * @param Request $request
     * @return Response
     */
    public function getIdentCodeAction(Request $request)
    {
        try {
            $serial  = intval($request->attributes->get('id'));
            $account = strval($request->attributes->get('account'));

            $identCode = $this->get('wk_dhl_api.b2b.ident_code');
            $identCode->setSerial($serial);

            $code = $identCode->get($account);

            $response = new IdentCodeResponse($code, IdentCode::format($code));

            return $this->generateResponse($response);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
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
        try {
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
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $result = $this->connection->cancelPickup($request->query->get('id'));

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentOrder = $this->serializer->deserialize(
                $request->getContent(),
                'Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType',
                'json'
            );

            $result = $this->connection->createShipmentDD($shipmentOrder);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->deleteShipmentDD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $shipmentOrder  = $this->serializer->deserialize(
                $request->getContent(),
                'Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType',
                'json'
            );

            $result = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->getLabelDD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
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
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentOrder = $this->serializer->deserialize(
                $request->getContent(),
                'Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType',
                'json'
            );

            $result = $this->connection->createShipmentTD($shipmentOrder);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->deleteShipmentTD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->getLabelTD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
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
        try {
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
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
    }

    /**
     * Do manifest action (day definite)
     *
     * @Method ("POST")
     * @Route (
     *      "/dd/manifest/{id}.{_format}",
     *      name="wk_dhl_api_b2b_do_manifest_dd",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     * @param Request $request
     * @return Response
     */
    public function doManifestDDAction(Request $request)
    {
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->doManifestDD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
    }

    /**
     * Do manifest action (time definite)
     *
     * @Method ("POST")
     * @Route (
     *      "/td/manifest/{id}.{_format}",
     *      name="wk_dhl_api_b2b_do_manifest_td",
     *      requirements={
     *          "id"="^\d{12}$",
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     * @param Request $request
     * @return Response
     */
    public function doManifestTDAction(Request $request)
    {
        try {
            $shipmentNumber = new ShipmentNumberType($request->query->get('id'));
            $result = $this->connection->doManifestTD($shipmentNumber);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
    }

    /**
     * Get manifest action (day definite)
     *
     * @Method ("GET")
     * @Route (
     *      "/dd/manifest/{from}/{to}.{_format}",
     *      name="wk_dhl_api_b2b_get_manifest_dd",
     *      requirements={
     *          "_format"="(xml|json)"
     *      },
     *      defaults={
     *          "_format"="json"
     *      }
     * )
     * @ParamConverter("from", options={"format": "Y-m-d"})
     * @ParamConverter("to", options={"format": "Y-m-d"})
     *
     * @param DateTime $from
     * @param DateTime $to
     * @return Response
     */
    public function getManifestDDAction(DateTime $from, DateTime $to)
    {
        try {
            $result = $this->connection->getManifestDD($from, $to);

            return $this->generateResponse($result);
        } catch(BadRequestHttpException $exception) {
            return $this->generateError($exception->getMessage(), $exception->getCode(), 400);
        } catch(\InvalidArgumentException $exception) {
            return $this->generateError($exception->getMessage(), 1001, 400);
        } catch(\Exception $exception) {
            return $this->generateError($exception->getMessage(), 1000);
        }
    }
}
