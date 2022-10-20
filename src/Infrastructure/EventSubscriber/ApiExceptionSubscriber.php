<?php

namespace App\Infrastructure\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {

        $this->logger->error($event->getThrowable());

        if ($event->getRequest()->getContentType() == 'json') {
            $error = json_decode($this->getErrorMessageFromEvent($event));
            $response = new JsonResponse($error);
        } else {
            $response = new Response( $this->getErrorMessageFromEvent($event));
        }

        $response->setStatusCode($this->getCode($event->getThrowable()));

        $event->setResponse($response);
    }

    private function getCode(\Throwable $throwable): int
    {
        return $throwable->getCode() ? $throwable->getCode() : Response::HTTP_BAD_REQUEST;
    }

    private function getErrorMessageFromEvent(ExceptionEvent $event)
    {
        $rawMessage = $this->getRawMessageFromEvent($event);
        $pos = strpos($rawMessage, "failed:");

        if ($pos !== false) {
            $array = explode("failed:",$rawMessage);
            return $array[1];
        } else {
            return $rawMessage;
        }
    }

    private function getRawMessageFromEvent(ExceptionEvent $event){
        if ($event->getThrowable()->getPrevious()) {
            return $event->getThrowable()->getPrevious()->getMessage();
        } else if ($event->getThrowable()->getMessage()) {
            return $event->getThrowable()->getMessage();
        } else {
            return "Empty error message";
        }
    }

}
