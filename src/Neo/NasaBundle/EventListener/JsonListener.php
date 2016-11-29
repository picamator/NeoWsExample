<?php
namespace Neo\NasaBundle\EventListener;

use Neo\NasaBundle\Controller\JsonAwareControllerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Json listener
 */
class JsonListener
{
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('Content-Type', 'application/json');
    }
}
