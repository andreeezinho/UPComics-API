<?php

namespace App\Domain\Models\Livro;

use App\Domain\Models\Traits\ModelTrait;

class Livro {

    use ModelTrait;

    public const TABLE = 'livros';

    public int $id;
    public ?string $uuid;
    public string $titulo;
    public string $descricao;
    public string $autor;
    public string $tipo;
    public string $capa;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function create(array $data) : Livro {
        $livro = new Livro();
        $livro->setFields($data);
        $livro->uuid = $data['uuid'] ?? $this->generateUUID();
        return $livro;
    }

}