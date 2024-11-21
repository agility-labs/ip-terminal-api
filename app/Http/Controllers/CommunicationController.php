<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse\ApiResponseMessagesEnum;
use App\Http\Requests\Communication\SendCommandRequest;
use App\Services\Communication\CommunicationService;
use Exception;
use Illuminate\Http\JsonResponse;

class CommunicationController extends Controller
{
    public function __construct(
        protected CommunicationService $communicationService
    ) {}

    public function sendMessage(SendCommandRequest $request): JsonResponse
    {
        try {
            $this->communicationService->sendMessage($request->validated());

            return response()->json(['success' => true, 'message' => ApiResponseMessagesEnum::SUCCESS_MESSAGE]);
        } catch (Exception $e) {

            return $this->responseError(ApiResponseMessagesEnum::ERROR_MESSAGE, 400);
        }
    }
}
