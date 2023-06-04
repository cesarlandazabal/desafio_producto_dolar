<?php

namespace App\Controller;

use App\Service\DollarService;
use ContainerAvUIfDd\App_KernelDevDebugContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


class DollarController extends AbstractController
{

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var DollarService  */
    private DollarService $dollarService;

    public function __construct(LoggerInterface $logger, DollarService $dollarService)
    {
        $this->logger        = $logger;
        $this->dollarService = $dollarService;
    }

    /**
     * @return Response
     * @Route("/api/dollar")
     * @ApiResource(dollar)
     */
    #[Route('/dollar', name: 'app_dollar')]
    public function index(): Response
    {
        try {
            return $this->json($this->dollarService->getDollarPrices());
        } catch (
            NotFoundExceptionInterface|
            ContainerExceptionInterface|
            ClientExceptionInterface|
            DecodingExceptionInterface|
            RedirectionExceptionInterface|
            ServerExceptionInterface|
            TransportExceptionInterface $e
        ) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => 'Error al obtener el servicio' . $e->getMessage()]);
        }

    }
}
