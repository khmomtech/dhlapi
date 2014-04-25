<?php
/**
 *
 * Created by PhpStorm.
 * User: thomas
 * Date: 25.04.14
 * Time: 16:16
 */

namespace Wk\DhlApiBundle\Lib\B2b\Converter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


class PickupBookingInformationConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
 
    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        $object = $this->serializer->deserialize(
            $request->request->get('pickup'),
            $class,
            'json'
        );
    }

    public function supports(ParamConverter $configuration)
    {
        if($configuration->getClass() == 'PickupBookingInformationType') {
            return true;
        }

        return false;
    }
}