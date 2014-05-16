<?php

namespace Wk\DhlApiBundle\Lib\B2b;

use DateTime;
use Exception;
use SoapClient;
use SoapHeader;
use SoapFault;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\AuthentificationType;

use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType;

use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;

use Wk\DhlApiBundle\Model\B2b\Request\BookPickupRequest;
use Wk\DhlApiBundle\Model\B2b\Request\CancelPickupRequest;
use Wk\DhlApiBundle\Model\B2b\Request\GetManifestDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\DoManifestDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\DoManifestTDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\GetExportDocDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\GetExportDocTDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\GetLabelDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\GetLabelTDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\CreateShipmentDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\CreateShipmentTDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\DeleteShipmentDDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\DeleteShipmentTDRequest;
use Wk\DhlApiBundle\Model\B2b\Request\UpdateShipmentDDRequest;

use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\CancelPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetManifestDDResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DoManifestResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetExportDocResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetLabelResponse;
use Wk\DhlApiBundle\Model\B2b\Response\CreateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DeleteShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\UpdateShipmentResponse;

/**
 * Class Connection
 * Implements the Singleton pattern
 */
class Connection
{
    /**
     * @var Connection
     */
    protected static $instance = null;

    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * @var  Version
     */
    protected static $version;

    /** params */
    protected $wsdl;
    protected $cisBase;
    protected $isUser;
    protected $isPassword;
    protected $cigUser;
    protected $cigPassword;
    protected $cigEndPoint;

    /**
     * @param Logger $logger
     */
    public function setLogger (Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Method to set the parameters for DHL B2B
     *
     * @param array $params
     */
    public function setParams (array $params)
    {
        $this->wsdl         = $params['wsdl_uri'];
        $this->cisBase      = $params['cis_base_uri'];
        $this->isUser       = $params['intraship']['user'];
        $this->isPassword   = $params['intraship']['password'];
        $this->cigUser      = $params['cig']['user'];
        $this->cigPassword  = $params['cig']['password'];
        $this->cigEndPoint  = $params['cig']['end_point_uri'];
    }

    /**
     * Instance of the Connection needed for service
     *
     * @return Connection
     */
    public static function getInstance ()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Static getter for the version because a version will not change dynamically
     *
     * @return Version
     */
    public static function getVersion()
    {
        if(is_null(self::$version)) {
            self::$version = new Version(1, 0);
        }

        return self::$version;
    }

    /**
     * Getter for the client
     *
     * @return SoapClient $client
     */
    public function getClient()
    {
        if(is_null($this->client)) {
            $this->initClient();
        }

        return $this->client;
    }

    /**
     * Setter for the client to provide the capability to set a mock client during unit testing
     *
     * @param SoapClient $client
     */
    public function setClient(SoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * Convenience method to book a pickup, wraps executeCommand
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $address
     * @param PickupOrdererType $orderer
     * @return BookPickupResponse
     */
    public function bookPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $address, PickupOrdererType $orderer = null)
    {
        $request = new BookPickupRequest($this->getVersion(), $bookingInformation, $address, $orderer);

        $response = $this->executeCommand('BookPickup', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to cancel a pickup, wraps executeCommand
     *
     * @param string $bookingNumber
     * @return CancelPickupResponse
     */
    public function cancelPickup($bookingNumber)
    {
        $request = new CancelPickupRequest($this->getVersion(), $bookingNumber);

        $response = $this->executeCommand('CancelPickup', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to create a national shipment, wraps executeCommand
     *
     * @param ShipmentOrderDDType $shipmentOrder
     * @return CreateShipmentResponse
     */
    public function createShipmentDD(ShipmentOrderDDType $shipmentOrder)
    {
        $request = new CreateShipmentDDRequest($this->getVersion(), $shipmentOrder);

        $response = $this->executeCommand('CreateShipmentDD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }
    /**
     * Convenience method to create a international shipment, wraps executeCommand
     *
     * @param ShipmentOrderTDType $shipmentOrder
     * @return CreateShipmentResponse
     */
    public function createShipmentTD(ShipmentOrderTDType $shipmentOrder)
    {
        $request = new CreateShipmentTDRequest($this->getVersion(), $shipmentOrder);

        $response = $this->executeCommand('CreateShipmentTD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to delete a national shipment, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return DeleteShipmentResponse
     */
    public function deleteShipmentDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DeleteShipmentDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DeleteShipmentDD', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to delete a international shipment, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return DeleteShipmentResponse
     */
    public function deleteShipmentTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DeleteShipmentTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DeleteShipmentTD', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to update a national shipment, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param ShipmentOrderDDType $shipmentOrder
     * @return UpdateShipmentResponse
     */
    public function updateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder)
    {
        $request = new UpdateShipmentDDRequest($this->getVersion(), $shipmentNumber, $shipmentOrder);

        $response = $this->executeCommand('UpdateShipmentDD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to get a national label, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return GetLabelResponse
     */
    public function getLabelDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetLabelDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetLabelDD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to get a international label, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return GetLabelResponse
     */
    public function getLabelTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetLabelTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetLabelTD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to get a national export document, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $docType
     * @return GetExportDocResponse
     */
    public function getExportDocDD(ShipmentNumberType $shipmentNumber, $docType)
    {
        $request = new GetExportDocDDRequest($this->getVersion(), $shipmentNumber, $docType);

        $response = $this->executeCommand('GetExportDocDD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to get a international export document, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $docType
     * @return GetExportDocResponse
     */
    public function getExportDocTD(ShipmentNumberType $shipmentNumber, $docType)
    {
        $request = new GetExportDocTDRequest($this->getVersion(), $shipmentNumber, $docType);

        $response = $this->executeCommand('GetExportDocTD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to do a national manifest, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return DoManifestResponse
     */
    public function doManifestDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DoManifestDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DoManifestDD', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to do a international manifest, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @return DoManifestResponse
     */
    public function doManifestTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DoManifestTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DoManifestTD', $request);
        if($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
    }

    /**
     * Convenience method to get a national manifest, wraps executeCommand
     *
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @return GetManifestDDResponse
     */
    public function getManifestDD(DateTime $fromDate, DateTime $toDate)
    {
        $request = new GetManifestDDRequest($this->getVersion(), $fromDate, $toDate);

        $response = $this->executeCommand('GetManifestDD', $request);
        if($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        return $response;
    }

    /**
     * @param string $commandName
     * @param object $request
     * @return mixed
     * @throws \Exception
     */
    private function executeCommand ($commandName, $request)
    {
        // Use the getter method instead of the attribute because it initializes the client if not happened yet
        $client = $this->getClient();

        try {
            return $client->__soapCall($commandName, array($request));
        } catch (Exception $exception) {
            $this->logger->debug($exception->getMessage());
            $this->logger->info($client->__getLastRequest());
            $this->logger->info($client->__getLastResponse());

            throw $exception;
        }
    }

    /**
     * Initialize the SOAP client to perform the different calls
     *
     * @return null|SoapClient
     */
    private function initClient ()
    {
        $options = array(
            'trace'         => true,
            'encoding'      => 'UTF-8',
            'soap_version'  => SOAP_1_2,
            'login'         => $this->cigUser,
            'password'      => $this->cigPassword,
            'location'      => $this->cigEndPoint,
        );

        $auth   = new AuthentificationType($this->isUser, $this->isPassword);
        $header = new SoapHeader($this->cisBase, 'Authentification', $auth);

        try {
            $this->client = new SoapClient($this->wsdl, $options);
            $this->client->__setSoapHeaders(array($header));
        } catch (Exception $e) {
            $this->client = null;
        }

        return $this->client;
    }
}
