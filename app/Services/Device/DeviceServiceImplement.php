<?php

namespace App\Services\Device;

use App\Repositories\Device\DeviceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelEasyRepository\Service;

class DeviceServiceImplement extends Service implements DeviceService
{
    public function __construct(
        protected DeviceRepository $deviceRepository
    ) {}

    public function getDevices(array $data): LengthAwarePaginator
    {
        $data = $this->deviceRepository->getDevices($data);

        return $data;
    }
}
