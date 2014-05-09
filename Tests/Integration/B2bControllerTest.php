<?php

namespace Wk\DhlApiBundle\Tests\Integration;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class B2bControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        // Create a client to call with
        $this->client = $this->createClient();
    }

    /**
     * Integration test for booking and canceling a pickup with JSON response
     *
     * @param int $expectedStatusCode
     * @param string $expectedRootNode
     * @param array $payload
     * @dataProvider providePickupData
     */
    public function testPickupJson($expectedStatusCode, $expectedRootNode, array $payload)
    {
        // At first send the request
        $this->client->request('POST', '/dhl/b2b/pickup.json', $payload);

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            sprintf(
                "Status code is %s instead of %d",
                $this->client->getResponse()->getStatusCode(),
                $expectedStatusCode
            )
        );

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        // Assert that the response contains real JSON
        $this->assertJson($client->getResponse()->getContent());

        // Decode JSON to use an array for further content validation
        $content = json_decode($client->getResponse()->getContent());

        // Assert that success message is set
        $this->assertArrayHasKey($expectedRootNode, $content);

        // Assert that the success message contains a 'true'
        $this->assertTrue($content['success']);

        // Assert that the response contains a confirmation number
        $this->assertArrayHasKey('id', $content);

        // Assert that the confirmation number is an integer value
        $this->assertTrue(is_int($content['id']));
    }

    /**
     * Integration test for booking and canceling a pickup with XML response
     *
     * @param int $expectedStatusCode
     * @param string $expectedRootNode
     * @param array $payload
     * @dataProvider providePickupData
     */
    public function testPickupXml($expectedStatusCode, $expectedRootNode, array $payload)
    {
        // Crawler works just with XML or HTML, so we use the crawler
        $crawler = $this->client->request('POST', '/dhl/b2b/pickup.xml', $payload);

        // Assert if the response status code isn't equal to the expected one
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode(),
            sprintf(
                "Status code is %s instead of %d",
                $this->client->getResponse()->getStatusCode(),
                $expectedStatusCode
            )
        );

        // Assert that the "Content-Type" header is "text/xml"
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'text/xml'));

        // Assert that the success message contains a 'true'
        $this->assertNotNull($crawler->filterXPath("//$expectedRootNode"));

        // Assert that the response contains a confirmation number which is an integer value
        $this->assertTrue(is_int($crawler->filterXPath('//id')->text()));
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
            'success',
            array(
                'address'   => $validAddress,
                'pickup'    => $validPickup,
            ),
        );

        // Add the booking information and same address for pickup and orderer
        $dataRows[] = array(
            200,
            'success',
            array(
                'address'   => $validAddress,
                'orderer'   => $validAddress,
                'pickup'    => $validPickup,
            ),
        );

        // Create an invalid payload with missing address
        $dataRows[] = array(
            400,
            'error',
            array(
                'pickup'    => $validPickup,
            ),
        );

        // Create an invalid payload with missing address but an orderer
        $dataRows[] = array(
            400,
            'error',
            array(
                'orderer'   => $validAddress,
                'pickup'    => $validPickup,
            ),
        );

        // Create an invalid payload with missing pickup information
        $dataRows[] = array(
            400,
            'error',
            array(
                'address'   => $validAddress,
            ),
        );

        // Create an invalid payload with wrong key
        $dataRows[] = array(
            400,
            'error',
            array(
                'address'   => $validAddress,
                'info'      => $validPickup,
            ),
        );

        return $dataRows;
    }
}
