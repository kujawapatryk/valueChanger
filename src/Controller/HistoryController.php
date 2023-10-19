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

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }
    #[Route('/exchange/values', name: 'app_exchange')]
    public function exchangeValues(Request $request, SerializerInterface $serializer): JsonResponse
    {

        $data = $request->getContent();
        try {
            $inputData = $serializer->deserialize($data, ExchangeValue::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => ['message' => 'Validation error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result = $this->historyRepository->exchangeAndSave($inputData->first, $inputData->second);

        return new JsonResponse($result);
    }
}
