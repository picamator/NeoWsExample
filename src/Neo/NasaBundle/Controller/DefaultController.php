<?php

namespace Neo\NasaBundle\Controller;

use Neo\NasaBundle\Model\Response\DefaultResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo find way to add headers for all responses
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function helloAction()
    {
        /** @var DefaultResponse $defaultResponse */
        $defaultResponse = $this->container->get('neo_nasa.model_response_default');
        $defaultResponse->setHello('world!');

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($defaultResponse,'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/neo/hazardous")
     */
    public function hazardousAction()
    {
        /** @var \Neo\NasaBundle\Service\HazardousService $service */
        $service = $this->container->get('neo_nasa.service_hazardous');
        $collection = $service->getHazardous();

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($collection,'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/neo/fastest")
     */
    public function fastestAction()
    {
        /** @var \Neo\NasaBundle\Service\FastestService $service */
        $service = $this->container->get('neo_nasa.service_fastest');
        $collection = $service->getFastest();

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($collection,'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
