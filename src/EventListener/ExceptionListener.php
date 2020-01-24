<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (strpos($event->getRequest()->getRequestUri(), '/api') !== 0) {
            return;
        }

        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {

            $message = $exception->getMessage();

            if ($exception->getPrevious()) {
                $message = "The requested URL was not found on the server";
            }

            $response = new JsonResponse([
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $message,
                ]
            ]);

            $response->setStatusCode($exception->getStatusCode());

            $event->setResponse($response);
        }
    }
}
