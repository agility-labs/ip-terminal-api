<?php

namespace App\Repositories\Command;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Command;

class CommandRepositoryImplement extends Eloquent implements CommandRepository{


    public function __construct(
        protected Command $model
        ){}

    public function saveCommand(array $data): object
    {
        return $this->model->create($data);
    }
}
