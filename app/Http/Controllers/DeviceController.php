<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse\ApiResponseMessagesEnum;
use App\Http\Requests\Device\GetDeviceRequest;
use App\Services\Device\DeviceService;
use Exception;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function __construct(
        protected DeviceService $deviceService
    ) {}

    public function getDevices(GetDeviceRequest $request): JsonResponse
    {
        try {
            $data = $this->deviceService->getDevices($request->validated());

            return $this->responseSuccess($data, ApiResponseMessagesEnum::SUCCESS_MESSAGE, 200);
        } catch (Exception $e) {
            info($e);

            return $this->responseError(ApiResponseMessagesEnum::ERROR_MESSAGE, 400);
        }
    }
}
