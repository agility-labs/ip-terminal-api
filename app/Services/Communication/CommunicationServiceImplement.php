<?php

namespace App\Services\Communication;

use App\Repositories\Device\DeviceRepository;
use App\Services\Socket\SocketService;
use LaravelEasyRepository\Service;
use RuntimeException;

class CommunicationServiceImplement extends Service implements CommunicationService
{
    public function __construct(
        protected DeviceRepository $deviceRepository,
        protected SocketService $socketService)
        {}

        public function sendMessage(array $data): array
        {
            $devices = $this->fetchDevices($data);
            $statuses = [];

            $socket = $this->socketService->getSocket();

            if (! $socket) {
                throw new RuntimeException('Socket UDP não está disponível');
            }

            foreach ($devices as $device) {
                $device = (object) $device;
                $sentMessage = $data['message'];

                $message = "$sentMessage;ID=$device->device_id;#8000;";
                $checksum = calculateChecksum($message);

                $message = ">$message*$checksum<";

                $sent = socket_sendto($socket, $message, strlen($message), 0, $device->ip, $device->port);

                $statuses[] = [
                    'device_ip' => $device->ip,
                    'device_port' => $device->port,
                    'status' => $sent !== false ? 'success' : 'failed',
                ];
            }

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
