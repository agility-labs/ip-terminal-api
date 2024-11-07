<?php

namespace App\Services\Communication;

use App\Repositories\Device\DeviceRepository;
use LaravelEasyRepository\Service;
use RuntimeException;

class CommunicationServiceImplement extends Service implements CommunicationService
{
    public function __construct(
        protected DeviceRepository $deviceRepository) {}

    public function sendMessage(array $data): array
    {

        $devices = $this->fetchDevices($data);
        $statuses = [];

        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (! $socket) {
            throw new RuntimeException('Não foi possível criar o socket UDP');
        }

        foreach ($devices as $device) {
            $device = (object) $device;

            $sent = socket_sendto($socket, $data['message'], strlen($data['message']), 0, $device->ip, $device->port);
            $statuses[] = [
                'device_ip' => $device->ip,
                'device_port' => $device->port,
                'status' => $sent !== false ? 'success' : 'failed',
            ];
        }

        socket_close($socket);

        return $statuses;
    }

    private function fetchDevices(array $data): array
    {
        if (isset($data['device_id'])) {
            return [$this->deviceRepository->findDeviceById($data['device_id'])];
        }

        return $this->deviceRepository->getAllDevices()->toArray();

    }
}
