<?php

namespace App\Console\Commands\Udp;

use App\Models\Command as ModelsCommand;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SocketListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:socket-listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para ativar socket';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $ip = config('app.udp_host');
        $port = config('app.udp_port');

        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (! $socket) {
            $this->error('Não foi possível criar o socket UDP');

            return;
        }

        if (! socket_bind($socket, $ip, $port)) {
            $this->error('Não foi possível fazer o bind ao socket');
            socket_close($socket);

            return;
        }

        $this->info("Listening for UDP packets on port $port...");
        $initialCount = 32770;
        $receivedData = null;

        socket_set_nonblock($socket);

        while (true) {
            $buffer = '';
            $from = '';
            $portFrom = 0;

            if (@socket_recvfrom($socket, $buffer, 512, 0, $from, $portFrom) !== false) {
                $this->info("Received packet from $from:$portFrom: $buffer");
                $receivedData = [
                    'ip' => $from,
                    'port' => $portFrom,
                    'buffer' => $buffer,
                ];

                $message = $this->mountAckMessage($buffer);
                socket_sendto($socket, $message, strlen($message), 0, $from, $portFrom);
                $this->info("Sent packet to $from:$portFrom: $message");
            }

            $commands = $this->fetchCommands();

            foreach($commands as $command) {

                $device = Device::where('device_id', $command->device_id)->first();

                if ($device) {
                    $routeMessage = $this->mountCustomMessage($command->device_id, $command->content, $initialCount);
                    socket_sendto($socket, $routeMessage, strlen($routeMessage), 0, $device->ip, $device->port);
                    $this->info("Sent packet to $device->ip:$device->port: $routeMessage");
                    $this->processCommand($command);
                }
            }

            if ($receivedData) {
                $this->handleDevices($receivedData);
            }

            $initialCount = $initialCount === 65535 ? 32770 : $initialCount;
        }

        socket_close($socket);
    }

    private function handleDevices(array $data): void
    {
        $deviceId = $this->extractDeviceIdFromPacket($data['buffer']);

        if ($deviceId) {
            Device::updateOrCreate(
                [
                    'device_id' => $deviceId,
                ],
                [
                    'ip' => $data['ip'],
                    'port' => $data['port'],
                    'last_packet' => Carbon::now(),
                ]
            );
        }
    }

    private function extractDeviceIdFromPacket(string $buffer): ?string
    {

        if (preg_match('/ID=([^;]+)/', $buffer, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function extractMessageIdFromPacket(string $buffer): string
    {
        if (preg_match('/#([A-F0-9]+);/', $buffer, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function mountAckMessage(string $buffer): string
    {
        $id = $this->extractDeviceIdFromPacket($buffer);
        $messageNumber = $this->extractMessageIdFromPacket($buffer);

        $message = "ACK;ID=$id;#$messageNumber;*";
        $checksum = calculateChecksum(">$message<");

        return ">$message$checksum<";
    }

    private function mountCustomMessage(string $deviceId, string $message, int $initialCount): string
    {
        $initialCount = dechex($initialCount);
        $newMessage = "$message;ID=$deviceId;#$initialCount;*";
        $checksum = calculateChecksum('>'.$newMessage.'<');

        return ">$newMessage$checksum<\n \r";
    }

    private function fetchCommands(): Collection
    {
        return ModelsCommand::where('processed', false)->get();
    }

    private function processCommand(object $command): void{
        $command->processed = true;
        $command->save();
    }
}
