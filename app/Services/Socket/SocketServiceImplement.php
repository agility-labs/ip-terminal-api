<?php

namespace App\Services\Socket;

use LaravelEasyRepository\Service;
use App\Repositories\Socket\SocketRepository;
use RuntimeException;

class SocketServiceImplement extends Service implements SocketService{

    protected $socket;

    public function __construct()
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!$this->socket) {
            throw new RuntimeException('Não foi possível criar o socket UDP');
        }
    }

    public function getSocket(): mixed
    {
        return $this->socket;
    }

    public function closeSocket(): void
    {
        if ($this->socket) {
            socket_close($this->socket);
        }
    }
}
