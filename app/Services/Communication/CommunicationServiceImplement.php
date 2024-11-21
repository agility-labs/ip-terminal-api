<?php

namespace App\Services\Communication;

use App\Repositories\Command\CommandRepository;
use App\Repositories\Device\DeviceRepository;
use LaravelEasyRepository\Service;
use RuntimeException;

class CommunicationServiceImplement extends Service implements CommunicationService
{
    public function __construct(
        protected DeviceRepository $deviceRepository,
        protected CommandRepository $commandRepository
        ) {}

    public function sendMessage(array $data): void
    {

        $devices = $this->fetchDevices($data);

        foreach ($devices as $device) {
            $device = (object) $device;

            $deviceData = [
                'device_id' => $device->device_id,
                'content' => $data['message']
            ];

            $this->commandRepository->create($deviceData);
        }

    }

    private function fetchDevices(array $data): array
    {
        if (isset($data['device_id'])) {
            return [$this->deviceRepository->findDeviceById($data['device_id'])];
        }

        return $this->deviceRepository->getAllDevices()->toArray();
    }
}
