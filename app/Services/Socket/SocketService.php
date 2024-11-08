<?php

namespace App\Services\Socket;

use LaravelEasyRepository\BaseService;

interface SocketService extends BaseService{

    public function getSocket(): mixed;

    public function closeSocket(): void;
}
