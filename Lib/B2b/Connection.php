<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12.05.14
 * Time: 11:01
 */

namespace Wk\DhlApiBundle\Lib\B2b;

use DateTime;
use Exception;
use SoapClient;
use SoapHeader;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Wk\DhlApiBundle\Model\B2b\StatusInformation;
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
 *
 * @package Wk\DhlApiBundle\Lib\B2b
 */
class Connection
{
    /**
     * Service singleton
     *
     * @var Connection
     */
    protected static $instance = null;

    /**
     * Logger
     *
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * SOAP client
     *
     * @var SoapClient
     */
    protected $client;

    /**
     * DHL API version
     *
     * @var  Version
     */
    protected static $version;

    /**
     * URI of WSDL file
     *
     * @var string
     */
    protected $wsdl;

    /**
     * URI of cis base file
     *
     * @var string
     */
    protected $cisBase;

    /**
     * Name of Intraship user
     *
     * @var string
     */
    protected $isUser;

    /**
     * Password of Intraship user
     *
     * @var string
     */
    protected $isPassword;

    /**
     * Name of HTTP auth user
     *
     * @var string
     */
    protected $cigUser;

    /**
     * Password of HTTP auth user
     *
     * @var string
     */
    protected $cigPassword;

    /**
     * URI of the end point
     *
     * @var string
     */
    protected $cigEndPoint;

    /**
     * Class constructor
     *
     * @param SoapClient $client
     */
    public function __construct(SoapClient $client = null)
    {
        $this->setClient($client);
    }

    /**
     * Setter for logger
     *
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Method to set the parameters for DHL B2B
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->wsdl = $params['wsdl_uri'];
        $this->cisBase = $params['cis_base_uri'];
        $this->isUser = $params['intraship']['user'];
        $this->isPassword = $params['intraship']['password'];
        $this->cigUser = $params['cig']['user'];
        $this->cigPassword = $params['cig']['password'];
        $this->cigEndPoint = $params['cig']['end_point_uri'];
    }

    /**
     * Instance of the Connection needed for service
     *
     * @return Connection
     */
    public static function getInstance()
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
        if (is_null(self::$version)) {
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
        if (is_null($this->client)) {
            $this->initClient();
        }

        return $this->client;
    }

    /**
     * Setter for the client to provide the capability to set a mock client during unit testing
     *
     * @param SoapClient $client
     */
    public function setClient(SoapClient $client = null)
    {
        $this->client = $client;
    }

    /**
     * Convenience method to book a pickup, wraps executeCommand
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType            $address
     * @param PickupOrdererType            $orderer
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function bookPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $address, PickupOrdererType $orderer = null)
    {
        $request = new BookPickupRequest($this->getVersion(), $bookingInformation, $address, $orderer);

        $response = $this->executeCommand('BookPickup', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to cancel a pickup, wraps executeCommand
     *
     * @param string $bookingNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function cancelPickup($bookingNumber)
    {
        $request = new CancelPickupRequest($this->getVersion(), $bookingNumber);

        $response = $this->executeCommand('CancelPickup', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to create a national shipment, wraps executeCommand
     *
     * @param ShipmentOrderDDType $shipmentOrder
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function createShipmentDD(ShipmentOrderDDType $shipmentOrder)
    {
        $request = new CreateShipmentDDRequest($this->getVersion(), $shipmentOrder);

        $response = $this->executeCommand('CreateShipmentDD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to create a international shipment, wraps executeCommand
     *
     * @param ShipmentOrderTDType $shipmentOrder
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function createShipmentTD(ShipmentOrderTDType $shipmentOrder)
    {
        $request = new CreateShipmentTDRequest($this->getVersion(), $shipmentOrder);

        $response = $this->executeCommand('CreateShipmentTD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to delete a national shipment, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function deleteShipmentDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DeleteShipmentDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DeleteShipmentDD', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to delete a international shipment, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function deleteShipmentTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DeleteShipmentTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DeleteShipmentTD', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to update a national shipment, wraps executeCommand
     *
     * @param ShipmentNumberType  $shipmentNumber
     * @param ShipmentOrderDDType $shipmentOrder
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function updateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder)
    {
        $request = new UpdateShipmentDDRequest($this->getVersion(), $shipmentNumber, $shipmentOrder);

        $response = $this->executeCommand('UpdateShipmentDD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to get a national label, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function getLabelDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetLabelDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetLabelDD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to get a international label, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function getLabelTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetLabelTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetLabelTD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to get a national export document, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string             $docType
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function getExportDocDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetExportDocDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetExportDocDD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to get a international export document, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string             $docType
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function getExportDocTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new GetExportDocTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('GetExportDocTD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to do a national manifest, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function doManifestDD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DoManifestDDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DoManifestDD', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to do a international manifest, wraps executeCommand
     *
     * @param ShipmentNumberType $shipmentNumber
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function doManifestTD(ShipmentNumberType $shipmentNumber)
    {
        $request = new DoManifestTDRequest($this->getVersion(), $shipmentNumber);

        $response = $this->executeCommand('DoManifestTD', $request);
        if ($response->Status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->Status->StatusMessage, null, $response->Status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Convenience method to get a national manifest, wraps executeCommand
     *
     * @param DateTime $fromDate
     * @param DateTime $toDate
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function getManifestDD(DateTime $fromDate, DateTime $toDate)
    {
        $request = new GetManifestDDRequest($this->getVersion(), $fromDate, $toDate);

        $response = $this->executeCommand('GetManifestDD', $request);
        if ($response->status->StatusCode !== 0) {
            throw new BadRequestHttpException($response->status->StatusMessage, null, $response->status->StatusCode);
        }

        // we need to convert it from stdClass to a serializable array
        return json_decode(json_encode($response), true);
    }

    /**
     * Main method to execute the command
     * It's used by all public convenience methods for the specific actions
     *
     * @param string $commandName
     * @param object $request
     *
     * @throws Exception
     * @return \stdClass
     */
    private function executeCommand($commandName, $request)
    {
        // Use the getter method instead of the attribute because it initializes the client if not happened yet
        $client = $this->getClient();

        try {
            $response = $client->__soapCall($commandName, array($request));
            if (!$response) {
                throw new Exception('SOAP request has no response');
            }

            return $response;
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
     * @throws Exception
     * @return null|SoapClient
     */
    private function initClient()
    {
        $options = array(
            'trace'        => true,
            'encoding'     => 'UTF-8',
            'soap_version' => SOAP_1_2,
            'login'        => $this->cigUser,
            'password'     => $this->cigPassword,
        );

        try {
            $auth = new AuthentificationType($this->isUser, $this->isPassword);
            $header = new SoapHeader($this->cisBase, 'Authentification', $auth);

            $this->client = new SoapClient($this->wsdl, $options);
            $this->client->__setSoapHeaders(array($header));
            $this->client->__setLocation($this->cigEndPoint);
        } catch (Exception $exception) {
            $this->logger->debug($exception->getMessage());
            $this->logger->info("Line: " . $exception->getLine());
            $this->logger->info("File: " . $exception->getFile());
            $this->logger->info("WSDL: " . $this->wsdl);
            $this->logger->info("Login: " . $this->cigUser);
            $this->logger->info("Location: " . $this->cigEndPoint);
            $this->logger->info("Namespace: " . $this->cisBase);
            $this->logger->info("Authentification: " . $this->isUser);

            throw new Exception('Initializing a SOAP client has been failed', 0, $exception);
        }

        return $this->client;
    }
}
