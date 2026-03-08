<?php

namespace App\Domain\Models\Volume;

use App\Domain\Models\Traits\ModelTrait;

class Volume {

    use ModelTrait;

    public const TABLE = 'volumes';

    public int $id;
    public ?string $uuid;
    public int $livros_id;
    public int $volume;
    public int $paginas;
    public string $path;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Volume {
        $volume = new Volume();
        $volume->setFields($data);
        $volume->uuid = $data['uuid'] ?? $this->generateUUID();
        return $volume;
    }

}