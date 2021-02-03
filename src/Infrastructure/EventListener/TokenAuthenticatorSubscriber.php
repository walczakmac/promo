<?php

namespace App\Infrastructure\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenAuthenticatorSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return array[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 10]
            ]
        ];
    }

    public function onKernelRequest(RequestEvent $event) : void
    {
        $token = $event->getRequest()->headers->get('X-token');
        if ($token !== $this->token) {
            $event->setResponse(new Response(null, Response::HTTP_UNAUTHORIZED));
            $event->stopPropagation();
        }
    }
}
