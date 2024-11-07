<?php

namespace App\Services\Communication;

use LaravelEasyRepository\BaseService;

interface CommunicationService extends BaseService
{
    public function sendMessage(array $data): array;
}
