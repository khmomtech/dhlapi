<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 27.05.14
 * Time: 12:10
 */

namespace Wk\DhlApiBundle\Tests\Unit;

use stdClass;
use Wk\DhlApiBundle\Lib\Base;

/**
 * Class BaseTest
 *
 * @package Wk\DhlApiBundle\Tests\Unit\B2b
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the toArray method
     *
     * @param array $expectedArray
     * @param Base  $object
     *
     * @dataProvider provideToArrayData
     */
    public function testToArray(array $expectedArray, Base $object)
    {
        $this->assertSame($expectedArray, $object->toArray());
    }

    /**
     * Tests the toStdClass method
     *
     * @param stdClass $expectedObject
     * @param Base     $object
     *
     * @dataProvider provideToStdClassData
     */
    public function testToStdClass(stdClass $expectedObject, Base $object)
    {
        $this->assertSame($expectedObject, $object->toStdClass());
    }

    /**
     * Provides data to test the toArray method
     *
     * @return array
     */
    public function provideToArrayData()
    {
        // Initial data set
        $dataSet = array();

        // Object to be used to perform 'toArray' method
        $object = new Base();
        $object->attribute1 = 'test';
        $object->attribute2 = 123;
        $object->attribute3 = true;
        $object->attribute4 = new Base();
        $object->attribute4->attribute1 = array(1, '1', true);
        $object->attribute4->attribute2 = new stdClass();
        $object->attribute4->attribute2->good = true;

        // Expected array to compare the result with
        $expectation = array(
            'attribute1' => 'test',
            'attribute2' => 123,
            'attribute3' => true,
            'attribute4' => array(
                'attribute1' => array(1, '1', true),
                'attribute2' => array('good' => true),
            ),
        );

        // Add the first combination
        $dataSet[] = array($expectation, $object);

        return $dataSet;
    }

    /**
     * Provides data to test the toStdClass method
     *
     * @return array
     */
    public function provideToStdClassData()
    {
        // Initial data set
        $dataSet = array();

        // Object to be used to perform 'toStdClass' method
        $object = new Base();
        $object->attribute1 = 'test';
        $object->attribute2 = 123;
        $object->attribute3 = true;
        $object->attribute4 = new Base();
        $object->attribute4->attribute1 = array(1, '1', true);
        $object->attribute4->attribute2 = new stdClass();
        $object->attribute4->attribute2->good = true;

        // Expected stdClass to compare the result with
        $expectation = new stdClass();
        $expectation->attribute1 = 'test';
        $expectation->attribute2 = 123;
        $expectation->attribute3 = true;
        $expectation->attribute4 = new stdClass();
        $expectation->attribute4->attribute1 = array(1, '1', true);
        $expectation->attribute4->attribute2 = new stdClass();
        $expectation->attribute4->attribute2->good = true;

        // Add the first combination
        $dataSet[] = array($expectation, $object);

        return $dataSet;
    }
}
 