<?php

namespace Wk\DhlApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\SerializerBundle\Serializer;

/**
 * Base class for all controller using object serialization
 *
 * This create a class variable `serializer` to allow serialization to JSON and XML
 * and a convenience method to create simple responses
 */
class SerializerController extends Controller
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Overwritten to ensure to have a service container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        if(is_null($container)) {
            $this->serializer = null;
        } else {
            $this->serializer = $this->get('jms_serializer');
        }
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
            $this->serializer->serialize(
                $response,
                $this->getRequest()->get('_format')
            )
        );
    }
}
