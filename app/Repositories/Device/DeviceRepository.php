<?php

namespace App\Repositories\Device;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelEasyRepository\Repository;

interface DeviceRepository extends Repository
{
    public function getDevices(array $data): LengthAwarePaginator;

    public function findDeviceById(string $deviceId): ?object;

    public function getAllDevices(): Collection;
}
