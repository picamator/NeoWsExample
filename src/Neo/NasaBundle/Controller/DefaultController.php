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
        $defaultResponse = $this->container->get('new_nasa.reponse_default');
        $defaultResponse->setHello('world!');

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($defaultResponse,'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
