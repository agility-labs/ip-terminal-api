<?php

namespace App\Repositories\Device;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelEasyRepository\Implementations\Eloquent;

class DeviceRepositoryImplement extends Eloquent implements DeviceRepository
{
    public function __construct(
        protected Device $model
    ) {}

    public function getDevices(array $data): LengthAwarePaginator
    {
        return $this->model->when(
            isset($data['search']),
            function ($q) use ($data) {
                $q->where('ip', 'ilike', '%'.$data['search'].'%')
                    ->orWhere('port', 'ilike', '%'.$data['search'].'%')
                    ->orWhere('device_id', 'ilike', '%'.$data['search'].'%');
            }
        )->paginate(isset($data['per_page']) ?? 9);
    }

    public function findDeviceById(string $deviceId): ?object
    {
        return $this->model->where('device_id', $deviceId)->first();
    }

    public function getAllDevices(): Collection
    {
        return $this->model->all();
    }
}
