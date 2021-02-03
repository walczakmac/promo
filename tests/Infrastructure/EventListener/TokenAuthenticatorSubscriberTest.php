<?php

namespace App\Tests\Infrastructure\EventListener;

use App\Infrastructure\EventListener\TokenAuthenticatorSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TokenAuthenticatorSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents()
    {
        $expectedEvents = [
            'kernel.request' => [
                ['onKernelRequest', 10]
            ]
        ];
        $this->assertSame($expectedEvents, TokenAuthenticatorSubscriber::getSubscribedEvents());
    }

    /**
     * @dataProvider provideForTestOnKernelRequest
     */
    public function testOnKernelRequest(string $token, ?Response $response, bool $isPropagationStopped)
    {
        $httpKernel = $this->getMockBuilder(HttpKernelInterface::class)->onlyMethods(['handle'])->getMock();
        $request = new Request();
        $request->headers->add(['X-token' => $token]);

        $subscriber = new TokenAuthenticatorSubscriber('random token');
        $event = new RequestEvent($httpKernel, $request, null);
        $subscriber->onKernelRequest($event);

        if (null === $response) {
            $this->assertNull($event->getResponse());
        } else {
            $this->assertInstanceOf(Response::class, $event->getResponse());
            $this->assertSame($response->getStatusCode(), $event->getResponse()->getStatusCode());
        }

        $this->assertSame($isPropagationStopped, $event->isPropagationStopped());
    }

    public function provideForTestOnKernelRequest(): array
    {
        return [
            ['random token', null, false],
            ['invalid token', new Response(null, Response::HTTP_UNAUTHORIZED), true]
        ];
    }
}
