<?php

namespace Wk\DhlApiBundle\Tests\Unit\B2b;

use Exception;
use DateTime;
use SoapFault;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\Account;
use Wk\DhlApiBundle\Model\B2b\Attendance;
use Wk\DhlApiBundle\Model\B2b\CommunicationType;
use Wk\DhlApiBundle\Model\B2b\Company;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\CreationState;
use Wk\DhlApiBundle\Model\B2b\DeletionState;
use Wk\DhlApiBundle\Model\B2b\ExportDocData;
use Wk\DhlApiBundle\Model\B2b\LabelData;
use Wk\DhlApiBundle\Model\B2b\ManifestState;
use Wk\DhlApiBundle\Model\B2b\NameType;
use Wk\DhlApiBundle\Model\B2b\NativeAddressType;
use Wk\DhlApiBundle\Model\B2b\Person;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\PieceInformation;
use Wk\DhlApiBundle\Model\B2b\ReceiverDDType;
use Wk\DhlApiBundle\Model\B2b\ReceiverTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDetailsDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDetailsTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentItemTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentItemDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType;
use Wk\DhlApiBundle\Model\B2b\ShipperTDType;
use Wk\DhlApiBundle\Model\B2b\ShipperDDType;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\ZipType;
use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\CancelPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\CreateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\UpdateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DeleteShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DoManifestResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetManifestDDResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetExportDocResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetLabelResponse;

/**
 * Class ConnectionTest
 *
 * @package Wk\DhlApiBundle\Tests\Unit\B2b
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * Set up before each test
     */
    public function setUp()
    {
        // Get SOAP setting from container parameters
        $options = array(
            'trace'        => true,
            'encoding'     => 'UTF-8',
            'soap_version' => SOAP_1_2,
            'login'        => 'geschaeftskunden_api',
            'password'     => 'Dhl_ep_test1',
            'location'     => 'https://cig.dhl.de/services/sandbox/soap',
        );

        // Create mock client from local WSDL file
        $wsdlFile = realpath(__DIR__ . '/../../Resources/geschaeftskundenversand-api-1.0.wsdl');
        $this->client = $this->getMockFromWsdl($wsdlFile, 'DhlApi', '', array('__soapCall'), false, $options);

        $logger = new Logger('DhlApi');

        $this->connection = new Connection($this->client);
        $this->connection->setLogger($logger);
    }

    /**
     * Tests creating a shipment (DD)
     *
     * @param ShipmentOrderDDType    $shipmentOrder
     * @param CreateShipmentResponse $expectedResponse
     * @param Exception              $expectedException
     *
     * @dataProvider provideCreateShipmentDDData
     */
    public function testCreateShipmentDD(ShipmentOrderDDType $shipmentOrder, CreateShipmentResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->createShipmentDD($shipmentOrder);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse->toStdClass());

            // Fire the function which is to be tested
            $response = $this->connection->createShipmentDD($shipmentOrder);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests creating a shipment (TD)
     *
     * @param ShipmentOrderTDType    $shipmentOrder
     * @param CreateShipmentResponse $expectedResponse
     * @param Exception              $expectedException
     *
     * @dataProvider provideCreateShipmentTDData
     */
    public function testCreateShipmentTD(ShipmentOrderTDType $shipmentOrder, CreateShipmentResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->createShipmentTD($shipmentOrder);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse->toStdClass());

            // Fire the function which is to be tested
            $response = $this->connection->createShipmentTD($shipmentOrder);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }

    }

    /**
     * Tests updating a shipment (DD)
     *
     * @param ShipmentNumberType     $shipmentNumber
     * @param ShipmentOrderDDType    $shipmentOrder
     * @param UpdateShipmentResponse $expectedResponse
     * @param Exception              $expectedException
     *
     * @dataProvider provideUpdateShipmentDDData
     */
    public function testUpdateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder, UpdateShipmentResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests deleting a shipment (DD)
     *
     * @param ShipmentNumberType     $shipmentNumber
     * @param DeleteShipmentResponse $expectedResponse
     * @param Exception              $expectedException
     *
     * @dataProvider provideDeleteShipmentData
     */
    public function testDeleteShipmentDD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->deleteShipmentDD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->deleteShipmentDD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests deleting a shipment (TD)
     *
     * @param ShipmentNumberType     $shipmentNumber
     * @param DeleteShipmentResponse $expectedResponse
     * @param Exception              $expectedException
     *
     * @dataProvider provideDeleteShipmentData
     */
    public function testDeleteShipmentTD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->deleteShipmentTD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->deleteShipmentTD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests getting a label (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetLabelResponse   $expectedResponse
     * @param Exception          $expectedException
     *
     * @dataProvider provideGetLabelData
     */
    public function testGetLabelDD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->getLabelDD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->getLabelDD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests getting a label (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetLabelResponse   $expectedResponse
     * @param Exception          $expectedException
     *
     * @dataProvider provideGetLabelData
     */
    public function testGetLabelTD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->getLabelTD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->getLabelTD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests booking a pickup
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType            $senderAddress
     * @param PickupOrdererType            $ordererAddress
     * @param BookPickupResponse           $expectedResponse
     * @param Exception                    $expectedException
     *
     * @dataProvider provideBookPickupData
     */
    public function testBookPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $senderAddress, PickupOrdererType $ordererAddress = null, BookPickupResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->bookPickup($bookingInformation, $senderAddress, $ordererAddress);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->bookPickup($bookingInformation, $senderAddress, $ordererAddress);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests canceling a pickup
     *
     * @param string               $confirmationNumber
     * @param CancelPickupResponse $expectedResponse
     * @param Exception            $expectedException
     *
     * @dataProvider provideCancelPickupData
     */
    public function testCancelPickup($confirmationNumber, CancelPickupResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->cancelPickup($confirmationNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->cancelPickup($confirmationNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests getting an export document (DD)
     *
     * @param ShipmentNumberType   $shipmentNumber
     * @param GetExportDocResponse $expectedResponse
     * @param Exception            $expectedException
     *
     * @dataProvider provideGetExportDocData
     */
    public function testGetExportDocDD(ShipmentNumberType $shipmentNumber, GetExportDocResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->getExportDocDD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->getExportDocDD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests getting an export document (TD)
     *
     * @param ShipmentNumberType   $shipmentNumber
     * @param GetExportDocResponse $expectedResponse
     * @param Exception            $expectedException
     *
     * @dataProvider provideGetExportDocData
     */
    public function testGetExportDocTD(ShipmentNumberType $shipmentNumber, GetExportDocResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->getExportDocTD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->getExportDocTD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests getting a manifest (DD)
     *
     * @param DateTime              $fromDate
     * @param DateTime              $toDate
     * @param GetManifestDDResponse $expectedResponse
     * @param Exception             $expectedException
     *
     * @dataProvider provideGetManifestDDData
     */
    public function testGetManifestDD(DateTime $fromDate, DateTime $toDate, GetManifestDDResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->getManifestDD($fromDate, $toDate);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->getManifestDD($fromDate, $toDate);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('StatusCode', $response['status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests doing a manifest (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DoManifestResponse $expectedResponse
     * @param Exception          $expectedException
     *
     * @dataProvider provideDoManifestData
     */
    public function testDoManifestDD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->doManifestDD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->doManifestDD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Tests doing a manifest (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DoManifestResponse $expectedResponse
     * @param Exception          $expectedException
     *
     * @dataProvider provideDoManifestData
     */
    public function testDoManifestTD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse = null, Exception $expectedException = null)
    {
        if ($expectedException) {
            $this->clientWillThrowException($expectedException);

            // Fire the function which is to be tested
            $this->connection->doManifestTD($shipmentNumber);
        } elseif ($expectedResponse) {
            // Stubbing
            $this->client->expects($this->any())->method('__soapCall')->willReturn($expectedResponse);

            // Fire the function which is to be tested
            $response = $this->connection->doManifestTD($shipmentNumber);

            // check if the response has a status information with the expected status code
            $this->assertArrayHasKey('Status', $response);
            $this->assertArrayHasKey('StatusCode', $response['Status']);
            $this->assertSame($expectedResponse->toArray(), $response);
        }
    }

    /**
     * Provides data to test day definite shipment order and the expected response for a shipment creation
     *
     * @return array
     */
    public function provideCreateShipmentDDData()
    {
        // Create misc
        $version = Connection::getVersion();
        $attendance = new Attendance('01');
        $zip = new ZipType('08150');
        $tomorrow = new DateTime('tomorrow');
        $yesterday = new DateTime('yesterday');

        // Create objects for valid requests
        $validItem = new ShipmentItemDDType(10, 50, 30, 15);
        $validPerson = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $validCompany = new Company('Musterfirma GmbH', null);
        $validName = new NameType($validPerson, $validCompany);
        $validCountry = new CountryType('Germany', 'DE', null);
        $validAddress = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $validCountry);
        $validComm = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validShipper = new ShipperDDType($validName, $validAddress, $validComm, '19');
        $validDetails = new ShipmentDetailsDDType('EPN', $tomorrow, '5000000000', $attendance, $validItem);
        $validReceiver = new ReceiverDDType($validName, $validAddress, $validComm);
        $validShipment = new ShipmentDDType($validDetails, $validShipper, $validReceiver);
        $validOrder = new ShipmentOrderDDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails = new ShipmentDetailsDDType('EPN', $yesterday, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment = new ShipmentDDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder = new ShipmentOrderDDType(1, $invalidShipment);

        /// Create response for success case
        $shipmentNumber = new ShipmentNumberType('1234567890987654321');
        $response = new CreateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $shipmentNumber, new PieceInformation($shipmentNumber))
        );

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($validOrder, $response),
            array($invalidOrder, null, $exception),
        );
    }

    /**
     * Provides data to test time definite shipment order and the expected response for a shipment creation
     *
     * @return array
     */
    public function provideCreateShipmentTDData()
    {
        // Create misc
        $version = Connection::getVersion();
        $account = new Account('5000000000');
        $zip = new ZipType('08150');
        $tomorrow = new DateTime('tomorrow');
        $yesterday = new DateTime('yesterday');

        // Create objects for valid requests
        $validItem = new ShipmentItemTDType(10, 50, 30, 15);
        $validPerson = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $validCompany = new Company('Musterfirma GmbH', null);
        $validName = new NameType($validPerson, $validCompany);
        $validCountry = new CountryType('Germany', 'DE', null);
        $validAddress = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $validCountry);
        $validComm = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validShipper = new ShipperTDType($validName, $validAddress, $validComm, '19');
        $validDetails = new ShipmentDetailsTDType('EPN', $tomorrow, $account, $validItem, 'Test shipment');
        $validReceiver = new ReceiverTDType($validCompany, $validShipper, $validComm);
        $validShipment = new ShipmentTDType($validDetails, $validShipper, $validReceiver);
        $validOrder = new ShipmentOrderTDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails = new ShipmentDetailsTDType('EPN', $yesterday, $account, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment = new ShipmentTDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder = new ShipmentOrderTDType(1, $invalidShipment);

        /// Create response for success case
        $shipmentNumber = new ShipmentNumberType('1234567890987654321');
        $response = new CreateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $shipmentNumber, new PieceInformation($shipmentNumber))
        );

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($validOrder, $response),
            array($invalidOrder, null, $exception),
        );
    }

    /**
     * Provides data to test booking a pickup
     *
     * @return array
     */
    public function provideBookPickupData()
    {
        // Misc
        $version = Connection::getVersion();

        // Set up the booking information
        $earliestPickup = new DateTime('tomorrow 09:00');
        $latestPickup = new DateTime('tomorrow 16:00');
        $validInformation = new PickupBookingInformationType('TDN', '5000000000', '01', $earliestPickup, $latestPickup, null, 'Hauptgebäude', 2, 0, 5, 2, 10, 15, 30, 5);
        $invalidInformation = new PickupBookingInformationType('TDN', '5000000000', '01', $earliestPickup, $latestPickup, null, 'Hauptgebäude', 2, 0, 5, 2, 10, 15, 30, 5);

        // Set the addresses where to pick up and of the orderer
        $validPerson = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $invalidPerson = new Person(null, 'Herr', null, 'Moritz', null);
        $company = new Company('Musterfirma GmbH', null);
        $validName = new NameType($validPerson, $company);
        $invalidName = new NameType($invalidPerson, $company);
        $country = new CountryType('Germany', 'DE', null);
        $zip = new ZipType('08150');
        $communication = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validAddress = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $country);
        $invalidAddress = new NativeAddressType(null, '1', null, $zip, 'Musterhausen', null, $country);
        $pickupAddress = new PickupAddressType($validName, $validAddress, $communication);
        $invalidPickup = new PickupAddressType($invalidName, $validAddress, $communication);
        $ordererAddress = new PickupOrdererType($validName, $validAddress, $communication);
        $invalidOrderer = new PickupOrdererType($validName, $invalidAddress, $communication);

        // Create response for success case
        $response = new BookPickupResponse($version, new StatusInformation(0, 'ok'), '1234567890987654321');

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($validInformation, $pickupAddress, null, $response),
            array($validInformation, $pickupAddress, $ordererAddress, $response),
            array($invalidInformation, $pickupAddress, $ordererAddress, null, $exception),
            array($validInformation, $invalidPickup, $ordererAddress, null, $exception),
            array($validInformation, $invalidPickup, $invalidOrderer, null, $exception),
        );
    }

    /**
     * Provides data to test canceling pickups
     *
     * @return array
     */
    public function provideCancelPickupData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success cases
        $response = new CancelPickupResponse($version, new StatusInformation(0, 'ok'));

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array('917968580', $response),
            array('9179685801', null, $exception),
        );
    }

    /**
     * Provides data to test shipment updates
     *
     * @return array
     */
    public function provideUpdateShipmentDDData()
    {
        // Create misc
        $version = Connection::getVersion();
        $attendance = new Attendance('01');
        $zip = new ZipType('08150');
        $tomorrow = new DateTime('tomorrow');
        $yesterday = new DateTime('yesterday');

        // Create objects for valid requests
        $validItem = new ShipmentItemDDType(10, 50, 30, 15);
        $validPerson = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $validCompany = new Company('Musterfirma GmbH', null);
        $validName = new NameType($validPerson, $validCompany);
        $validCountry = new CountryType('Germany', 'DE', null);
        $validAddress = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $validCountry);
        $validComm = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validShipper = new ShipperDDType($validName, $validAddress, $validComm, '19');
        $validDetails = new ShipmentDetailsDDType('EPN', $tomorrow, '5000000000', $attendance, $validItem);
        $validReceiver = new ReceiverDDType($validCompany, $validShipper, $validComm);
        $validShipment = new ShipmentDDType($validDetails, $validShipper, $validReceiver);
        $validOrder = new ShipmentOrderDDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails = new ShipmentDetailsDDType('EPN', $yesterday, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment = new ShipmentDDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder = new ShipmentOrderDDType(1, $invalidShipment);

        /// Create response for success case
        $shipmentNumber = new ShipmentNumberType('1234567890987654321');
        $response = new UpdateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $shipmentNumber, new PieceInformation($shipmentNumber))
        );

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($shipmentNumber, $validOrder, $response),
            array($shipmentNumber, $invalidOrder, null, $exception),
        );
    }

    /**
     * Provides data to test deletion a shipment
     *
     * @return array
     */
    public function provideDeleteShipmentData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $shipmentNumber = new ShipmentNumberType('00340433836008760131');
        $successStatus = new StatusInformation(0, 'ok');
        $successDeletion = new DeletionState($shipmentNumber, $successStatus);
        $successResponse = new DeleteShipmentResponse($version, $successStatus, $successDeletion);

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($shipmentNumber, $successResponse),
            array($shipmentNumber, null, $exception),
        );
    }

    /**
     * Provides data to test getting a label
     *
     * @return array
     */
    public function provideGetLabelData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $shipmentNumber = new ShipmentNumberType('00340433836008759937');
        $successStatus = new StatusInformation(0, 'ok');
        $successData = new LabelData($shipmentNumber, $successStatus, 'http://test-intraship.dhl.com:80/cartridge.61/WSPrint?code=03EBF2F0F0383A4E689393267E5098E30314A0F71D32D6E7');
        $successResponse = new GetLabelResponse($version, $successStatus, $successData);

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($shipmentNumber, $successResponse),
            array($shipmentNumber, null, $exception),
        );
    }

    /**
     * Provides data to test getting manifests
     *
     * @return array
     */
    public function provideGetManifestDDData()
    {
        // Misc
        $version = Connection::getVersion();
        $today = new DateTime('today');
        $tomorrow = new DateTime('tomorrow');

        // Create response for success cases
        $response = new GetManifestDDResponse(
            $version,
            new StatusInformation(0, 'ok'),
            null // TODO: use a sample PDF content (base64 encoded)
        );

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($today, $tomorrow, $response),
            array($tomorrow, $tomorrow, null, $exception),
        );
    }

    /**
     * Provides data to test doing manifests
     *
     * @return array
     */
    public function provideDoManifestData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $shipmentNumber = new ShipmentNumberType('00340433836008824970');
        $successStatus = new StatusInformation(0, 'ok');
        $successManifest = new ManifestState($shipmentNumber, $successStatus);
        $response = new DoManifestResponse($version, $successStatus, $successManifest);

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($shipmentNumber, $response),
            array($shipmentNumber, null, $exception),
        );
    }

    /**
     * Provides data to test getting export documents
     *
     * @return array
     */
    public function provideGetExportDocData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $shipmentNumber = new ShipmentNumberType('960701151320');
        $successStatus = new StatusInformation(0, 'ok');
        $exportData = new ExportDocData($shipmentNumber, $successStatus);
        $response = new GetExportDocResponse($version, $successStatus, $exportData);

        // Create an exception for a bad request
        $exception = new BadRequestHttpException('General error', null, 1000);

        return array(
            array($shipmentNumber, $response),
            array($shipmentNumber, null, $exception),
        );
    }

    /**
     * Helper to handle expected exceptions
     *
     * @param Exception $exception
     */
    private function clientWillThrowException(Exception $exception)
    {
        // Stubbing
        $soapFault = new SoapFault(strval($exception->getCode()), $exception->getMessage());
        $this->client->expects($this->any())->method('__soapCall')->willThrowException($soapFault);

        // Define the expectation
        $this->setExpectedException(get_class($exception), $exception->getMessage(), $exception->getCode());
    }
}
