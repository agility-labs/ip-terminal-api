<?php

namespace App\Services\Device;

use Illuminate\Pagination\LengthAwarePaginator;
use LaravelEasyRepository\BaseService;

interface DeviceService extends BaseService
{
    public function getDevices(array $data): LengthAwarePaginator;
}
