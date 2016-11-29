<?php
namespace Neo\NasaBundle\Controller;

use Neo\NasaBundle\Model\Response\DefaultResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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

        return new Response($data);
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

        return new Response($data);
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

        return new Response($data);
    }
}
