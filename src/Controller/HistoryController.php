<?php

namespace App\Controller;

use App\DTO\ExchangeValueRequest;
use App\DTO\ShowHistoryRequest;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HistoryController extends AbstractController
{
    private HistoryRepository $historyRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(HistoryRepository $historyRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->historyRepository = $historyRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
    #[Route('/exchange/values', name: 'app_exchange', methods: ['POST'])]
    public function exchangeValues(Request $request): JsonResponse
    {

        $data = $request->getContent();
        try {
            $inputData = $this->serializer->deserialize($data, ExchangeValueRequest::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => ['message' => 'Validation error.']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result = $this->historyRepository->exchangeAndSave($inputData->first, $inputData->second);

        return new JsonResponse($result);
    }

    #[Route('/exchange/history', name: 'app_exchange_history', methods: ['GET'])]
    public function showHistory(Request $request): JsonResponse
    {

        $showHistoryRequest = new ShowHistoryRequest();
        $showHistoryRequest->sort = $request->query->get('sort', 'id');
        $showHistoryRequest->direction = $request->query->get('direction', 'asc');
        $showHistoryRequest->page = $request->query->getInt('page', 1);
        $showHistoryRequest->limit = $request->query->getInt('limit', 10);

        $errors = $this->validator->validate($showHistoryRequest);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->historyRepository->getPaginatedHistory(
            $showHistoryRequest->sort,
            $showHistoryRequest->direction,
            $showHistoryRequest->page,
            $showHistoryRequest->limit,
        );
        $data = $this->serializer->serialize($data, 'json');

       return new JsonResponse($data, Response::HTTP_OK, [], true);
    }


}
