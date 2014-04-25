<?php

namespace Wk\DhlApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class B2bControllerTest extends WebTestCase
{
    public function testBookPickup()
    {
        // Address data for pickup address and orderer contact
        $address = array(
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

        // Add the booking information and address to the request parameters
        $parameters = array(
            'address'   => $address,
            'orderer'   => $address,
            'pickup'   => array(
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
            ),
        );

        // Create a client to call with
        $client = $this->createClient();

        // Crawler works just with XML or HTML, so we choose the xml format
        $crawler = $client->request('POST', '/dhl/b2b/pickup.xml', $parameters);

        // Assert that the response status code is 2xx
        $this->assertTrue(
            $client->getResponse()->isSuccessful(),
            sprintf(
                'Status code is %s instead of 2XX',
                $client->getResponse()->getStatusCode()
            )
        );

        // Assert that the "Content-Type" header is "text/xml"
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'text/xml'));

        // Assert that the success message contains a 'true'
        $this->assertEquals('true', $crawler->filterXPath('//success')->text());

        // Assert that the response contains a confirmation number which is an integer value
        $this->assertTrue(is_int($crawler->filterXPath('//id')->text()));

        // Now test with JSON
        $client->request('POST', '/dhl/b2b/pickup.json', $parameters);

        // Assert that the response status code is 2xx
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        // Assert that the response contains real JSON
        $this->assertJson($client->getResponse()->getContent());

        // Decode JSON to use an array for further content validation
        $content = json_decode($client->getResponse()->getContent());

        // Assert that success message is set
        $this->assertArrayHasKey('success', $content);

        // Assert that the success message contains a 'true'
        $this->assertTrue($content['success']);

        // Assert that the response contains a confirmation number
        $this->assertArrayHasKey('id', $content);

        // Assert that the confirmation number is an integer value
        $this->assertTrue(is_int($content['id']));
    }
}
