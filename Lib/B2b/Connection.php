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

/**
 * Class Connection
 * Implements the Singleton pattern
 */
class Connection
{
    const MAX_ATTEMPTS = 10;

    protected static $instance = null;

    /** @var \Monolog\Logger */
    private $logger;

    /** @var  SoapClient */
    protected $client;

    /** @var  Version */
    protected $version;

    /** params */
    protected $wsdl;
    protected $cisBase;
    protected $isUser;
    protected $isPassword;
    protected $ekp;
    protected $attendance;
    protected $cigUser;
    protected $cigPassword;
    protected $cigEndPoint;

    /**
     * Constructor for the class
     */
    public function __construct()
    {
    }

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
        $this->ekp          = $params['ekp'];
        $this->attendance   = $params['attendance'];
        $this->wsdl         = $params['wsdl_uri'];
        $this->cisBase      = $params['cis_base_uri'];
        $this->isUser       = $params['intraship']['user'];
        $this->isPassword   = $params['intraship']['password'];
        $this->cigUser      = $params['cig']['user'];
        $this->cigPassword  = $params['cig']['password'];
        $this->cigEndPoint  = $params['cig']['end_point_uri'];
    }

    /**
     * Instance of the Connection
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
     * Getter for the version
     *
     * @return Version
     */
    public function getVersion()
    {
        if(is_null($this->version)) {
            $this->version = new Version(1, 0);
        }

        return $this->version;
    }

    /**
     * Convenience method to book a pickup, wraps executeCommand
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $address
     * @param PickupOrdererType $orderer
     * @return BookPickupResponse
     */
    public function bookPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $address, PickupOrdererType $orderer)
    {
        $request = new BookPickupRequest($this->getVersion(), $bookingInformation, $address, $orderer);

        return $this->executeCommand('BookPickup', $request);
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

        return $this->executeCommand('CancelPickup', $request);
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

        return $this->executeCommand('CreateShipmentDD', $request);
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

        return $this->executeCommand('CreateShipmentTD', $request);
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

        return $this->executeCommand('DeleteShipmentDD', $request);
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

        return $this->executeCommand('DeleteShipmentTD', $request);
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

        return $this->executeCommand('UpdateShipmentDD', $request);
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

        return $this->executeCommand('GetLabelDD', $request);
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

        return $this->executeCommand('GetLabelTD', $request);
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

        return $this->executeCommand('GetExportDocDD', $request);
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

        return $this->executeCommand('GetExportDocTD', $request);
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

        return $this->executeCommand('DoManifestDD', $request);
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

        return $this->executeCommand('DoManifestTD', $request);
    }

    /**
     * Convenience method to get a national manifest, wraps executeCommand
     *
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @return GetManifestResponse
     */
    public function getManifestDD(DateTime $fromDate, DateTime $toDate)
    {
        $range   = new ManifestDateRange($fromDate->format('Y-m-d'), $toDate->format('Y-m-d'));
        $request = new GetManifestDDRequest($this->getVersion(), $fromDate->format('Y-m-d'), $range);

        return $this->executeCommand('GetManifestDD', $request);
    }

    /**
     * @param string    $commandName
     * @param object    $request
     * @return mixed
     * @throws SoapFault
     * @throws BadRequestHttpException
     */
    private function executeCommand ($commandName, $request)
    {
        // Use the getter method instead of the attribute because it initializes the client if not happened yet
        $client = $this->getClient();

        try {
            $response = $client->__soapCall($commandName, array($request));
        } catch (SoapFault $soapException) {
            $this->logger->debug($e->getMessage());
            $this->logger->info($client->__getLastRequest());
            $this->logger->info($client->__getLastResponse());

            throw $soapException;
        }

        if(@$response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        return $response;
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

        $auth   = new AuthentificationType($this->isUser, $this->isPassword, $this->ekp);
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
