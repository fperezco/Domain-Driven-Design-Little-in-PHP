<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Shared;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Query\QueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractQueryController
{
    private QueryBusInterface $queryBus;
    protected Request $request;
    protected SerializerInterface $serializer;
    protected NormalizerInterface $normalizer;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer,
        QueryBusInterface $queryBus
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
        $this->queryBus = $queryBus;
    }

    protected function dispatch(QueryInterface $query)
    {
        return $this->queryBus->dispatch($query);
    }

    protected function getNormalizeJsonResponse($results){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getUuid();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = new Response($serializer->serialize($results, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
