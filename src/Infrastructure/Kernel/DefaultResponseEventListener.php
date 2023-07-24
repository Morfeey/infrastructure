<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Kernel;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\DefaultExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\NoContent\NoContentException;
use App\Bundles\InfrastructureBundle\Infrastructure\Response\AppJsonResponse;
use App\Bundles\InfrastructureBundle\Infrastructure\Response\Service\ResponseCodeService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final readonly class DefaultResponseEventListener implements EventSubscriberInterface
{
    public function __construct(
        private ResponseCodeService $responseCodeService,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onResponse', 2],
            KernelEvents::EXCEPTION => ['onException', 2]
        ];
    }

    public function onResponse(ResponseEvent $event): void
    {

        if (!$event->getResponse()->isOk() || !($event->getResponse() instanceof JsonResponse)) {
            return;
        }

        $arrayResponse = $event->getResponse()->getContent() ? json_decode($event->getResponse()->getContent(), true) : [];
        if (!$arrayResponse) {
            (new EventDispatcher())->dispatch(
                (new ExceptionEvent(
                    $event->getKernel(),
                    $event->getRequest(),
                    $event->getRequestType(),
                    new NoContentException()
                )),
                KernelEvents::EXCEPTION
            );
        }

        $event->setResponse($this->createJsonResponse($event->getResponse()));
    }

    public function onException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $this->logger->critical($throwable);
        if (!($throwable instanceof DefaultExceptionInterface)) {
            $throwable = new DefaultException($throwable->getMessage(), $throwable->getCode());
        }

        $response = $this->createJsonResponse($event->getResponse(), $throwable);
        if ($event->getRequest()->getRealMethod() === Request::METHOD_OPTIONS) {
            $response->setStatusCode(200);
        }

        $event->setResponse($response);
    }

    private function createJsonResponse(?Response $response = null, ?Throwable $throwable = null): Response
    {
        $data = [];
        $meta = [];
        $content = $response instanceof  JsonResponse ? json_decode($response->getContent(), true) : $response?->getContent() ?? [];
        switch ($response) {
            case $response instanceOf AppJsonResponse:
                $data = $content['data'];
                $meta = $content['meta'];
                break;
            case $response instanceof Response:
                $data = $content;
                break;
            default:
                break;
        }

        $jsonResponse = new JsonResponse([
            'meta' => $meta,
            'data' => $data,
            'message' => $throwable?->getMessage(),
            'trace' => $throwable?->getTrace()
        ]);

        if ($response !== null) {
            $jsonResponse
                ->setStatusCode($response->getStatusCode())
                ->headers->replace($response->headers->all());
        }

        if ($throwable) {
            $jsonResponse->setStatusCode($this->responseCodeService->getByThrowable($throwable));
        }

        return $jsonResponse;
    }
}
