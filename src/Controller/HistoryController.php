<?php

namespace App\Controller;

use App\DTO\ExchangeValue;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HistoryController extends AbstractController
{
    private HistoryRepository $historyRepository;
    private SerializerInterface $serializer;


    public function __construct(HistoryRepository $historyRepository, SerializerInterface $serializer)
    {
        $this->historyRepository = $historyRepository;
        $this->serializer = $serializer;
    }
    #[Route('/exchange/values', name: 'app_exchange', methods: ['POST'])]
    public function exchangeValues(Request $request): JsonResponse
    {

        $data = $request->getContent();
        try {
            $inputData = $this->serializer->deserialize($data, ExchangeValue::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => ['message' => 'Validation error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result = $this->historyRepository->exchangeAndSave($inputData->first, $inputData->second);

        return new JsonResponse($result);
    }

    #[Route('/exchange/history', name: 'app_exchange_history', methods: ['GET'])]
    public function showHistory(): JsonResponse
    {
       $data = $this->historyRepository->getAll();
       $data = $this->serializer->serialize($data, 'json');

       return new JsonResponse($data, Response::HTTP_OK, [], true);
    }


}
