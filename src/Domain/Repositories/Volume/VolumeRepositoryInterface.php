<?php

namespace App\Domain\Repositories\Volume;

interface VolumeRepositoryInterface {

    public function all(array $params);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

}