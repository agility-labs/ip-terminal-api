<?php

namespace App\Repositories\Command;

use LaravelEasyRepository\Repository;

interface CommandRepository extends Repository{

   public function saveCommand(array $data): object;
}
