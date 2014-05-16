<?php

namespace Wk\DhlApiBundle\Tests\Integration;

use DOMDocument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Wk\DhlApiBundle\Lib\B2b\IdentCode;
use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\ErrorResponse;
use Wk\DhlApiBundle\Model\B2b\Response\IdentCodeResponse;
use JMS\Serializer\Serializer;
use Wk\DhlApiBundle\Model\B2b\Version;

/**
 * Class B2bControllerTest
 * @package Wk\DhlApiBundle\Tests\Integration
 */
class B2bControllerTest extends WebTestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Client
     */
    private $client;

    /**
     * Set up method which is running before each test method
     */
    public function setUp()
    {
        // Client is needed in each test
        $this->client = $this->createClient();
        $this->serializer = $this->client->getContainer()->get('jms_serializer');
    }

    /**
     * Tests getting ident codes with JSON response
     *
     * @param int $expectedStatusCode
     * @param string $account
     * @param int $serial
     * @param string $expectedResponse
     * @dataProvider provideIdentCodeData
     */
    public function testIdentCodeJson($expectedStatusCode, $account, $serial, $expectedResponse)
    {
        $this->client->request('GET', sprintf('/dhl/b2b/identcode/%s/%d.json', $account, $serial));

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Content-Type', 'application/json'),
            $this->client->getResponse()->headers->get('Content-Type')
        );

        // Assert that the response contains real JSON
        $this->assertJson($this->client->getResponse()->getContent());

        // Assert that the expected response is equal to the current one
        $this->assertJsonStringEqualsJsonString(
            $this->serializer->serialize($expectedResponse, 'json'),
            $this->client->getResponse()->getContent(),
            'JSON is not equal'
        );
    }

    /**
     * Tests getting ident codes with XML response
     *
     * @param int $expectedStatusCode
     * @param string $account
     * @param int $serial
     * @param string $expectedResponse
     * @dataProvider provideIdentCodeData
     */
    public function testIdentCodeXml($expectedStatusCode, $account, $serial, $expectedResponse)
    {
        $this->client->request('GET', sprintf('/dhl/b2b/identcode/%s/%d.xml', $account, $serial));

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Content-Type', 'text/xml; charset=UTF-8'),
            $this->client->getResponse()->headers->get('Content-Type')
        );

        // Check if it's a valid XML
        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($this->client->getResponse()->getContent()), 'Is no valid XML');

        // Assert that the expected response is equal to the current one
        $this->assertEquals(
            $this->serializer->serialize($expectedResponse, 'xml'),
            $this->client->getResponse()->getContent(),
            'XML is not equal'
        );
    }

    /**
     * Data provider to provide data to test getting ident codes via the controller
     *
     * @return array
     */
    public function provideIdentCodeData()
    {
        // Start with a test with a likely not configured account name
        $dataSet = array(
            array(400, 'alikelyneverusedkey', 99999, new ErrorResponse(1001, "Account 'alikelyneverusedkey' is not configured"))
        );

        // Loop and test all configured accounts
        $accounts = $this->createClient()->getKernel()->getContainer()->getParameter('wk_dhl_api.b2b.accounts');
        foreach($accounts as $key => $account) {
            // Add items to data set with valid data
            $code = $account.'0099999';
            $code .= IdentCode::getParityNumber($code);
            $dataSet[] = array(200, $key, 99999,  new IdentCodeResponse($code, IdentCode::format($code)));

            $code = $account.'0000815';
            $code .= IdentCode::getParityNumber($code);
            $dataSet[] = array(200, $key, 815,    new IdentCodeResponse($code, IdentCode::format($code)));

            $code = $account.'0000003';
            $code .= IdentCode::getParityNumber($code);
            $dataSet[] = array(200, $key, 3,      new IdentCodeResponse($code, IdentCode::format($code)));

            // add data sets with invalid parameters
            $dataSet[] = array(400, $key, 999999, new ErrorResponse(1001, 'Serial number contains more than 5 digits. Start with 1 again or use modulus 100000'));
            $dataSet[] = array(400, $key, 0,      new ErrorResponse(1001, 'Serial number is no unsigned integer'));
        }

        return $dataSet;
    }

    /**
     * Integration test for booking and canceling a pickup with JSON response
     *
     * @param int $expectedStatusCode
     * @param array $payload
     * @dataProvider providePickupData
     */
    public function testPickupJson($expectedStatusCode, array $payload)
    {
        // At first send the request
        $this->client->request('POST', '/dhl/b2b/pickup.json', array(), array(), array(), json_encode($payload));

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Content-Type', 'application/json'),
            $this->client->getResponse()->headers->get('Content-Type')
        );

        // Assert that the response contains real JSON
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * Integration test for booking and canceling a pickup with XML response
     *
     * @param int $expectedStatusCode
     * @param array $payload
     * @dataProvider providePickupData
     */
    public function testPickupXml($expectedStatusCode, array $payload)
    {
        $this->client->request('POST', '/dhl/b2b/pickup.xml', array(), array(), array(), json_encode($payload));

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        // Assert that the "Content-Type" header is "text/xml"
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Content-Type', 'text/xml; charset=UTF-8'),
            $this->client->getResponse()->headers->get('Content-Type')
        );
    }

    /**
     * Data provider to get booking information, sender address, orderer address and the expected status code for a pickup
     *
     * @return array
     */
    public function providePickupData()
    {
        // Array to fill with arrays containing payload and expected status code
        $dataRows = array();

        // Address data for pickup address and orderer contact
        $validAddress = array(
            'zip'       => '10117',
            'city'      => 'Musterhausen',
            'street'    => array(
                'name'      => 'Musterstraße',
                'number'    => '999a',
            ),
            'country'   => array(
                'code'      => 'DE',
                'name'      => 'Deutschland',
            ),
            'person'    => array(
                'name'      => array(
                    'first'     => 'Max',
                    'last'      => 'Mustermann',
                )
            ),
            'company'   => array(
                'name'      => 'Musterfirma',
                'addition'  => '999. Stock',
            ),
            'communication' => array(
                'phone'         => '+4930-33215-0',
                'email'         => 'max@muster.de',
            ),
        );

        $validPickup = array(
            'product'   => 'DDN',
            'account'   => '5000000000',
            'attendance'=> '01',
            'date'      => date('Y-m-d', strtotime('tomorrow')),
            'location'  => 'Hauptgebäude',
            'count'     => 1,
            'time'      => array(
                'start'     => strtotime('09:00 +1day'),
                'end'       => strtotime('16:00 +1day'),
            ),
            'amount'    => array(
                'pieces'    => 1,
                'pallets'   => 0,
            ),
            'weight'    => array(
                'piece'     => 4,
                'total'     => 4,
            ),
            'max'       => array(
                'length'    => 70,
                'width'     => 30,
                'height'    => 15,
            ),
        );

        // Add the booking information and address for pickup
        $dataRows[] = array(
            200,
            array(
                'address'       => $validAddress,
                'information'   => $validPickup,
            ),
        );

        // Add the booking information and same address for pickup and orderer
        $dataRows[] = array(
            200,
            array(
                'address'       => $validAddress,
                'orderer'       => $validAddress,
                'information'   => $validPickup,
            ),
        );

        // Create an invalid payload with missing address
        $dataRows[] = array(
            500,
            array(
                'information'   => $validPickup,
            ),
        );

        // Create an invalid payload with missing address but an orderer
        $dataRows[] = array(
            500,
            array(
                'orderer'       => $validAddress,
                'information'   => $validPickup,
            ),
        );

        // Create an invalid payload with missing pickup information
        $dataRows[] = array(
            500,
            array(
                'address'   => $validAddress,
            ),
        );

        // Create an invalid payload with wrong key
        $dataRows[] = array(
            500,
            array(
                'address'   => $validAddress,
                'info'      => $validPickup,
            ),
        );

        return $dataRows;
    }
}
