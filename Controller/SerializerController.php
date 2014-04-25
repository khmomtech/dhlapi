<?php

namespace Wk\DhlApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Base class for all controller using object serialization
 *
 * This create a class variable `serializer` to allow serialization to JSON and XML
 * and a convenience method to create simple responses
 */
class SerializerController extends Controller
{

    // @var Serializer for class
    protected $serializer;

    /**
     * Class constructor
     * Initializes the class serializer
     */
    public function __construct()
    {
        $encoders = array(
            'xml'  => new XmlEncoder(),
            'json' => new JsonEncoder()
        );
        $normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * Convenience method to return simple responses
     *
     * @param mixed $response
     * @return Response
     */
    public function generateResponse($response)
    {
        return new Response(
            $this->serializer->encode(
                $response,
                $this->getRequest()->get('_format')
            )
        );
    }
}
