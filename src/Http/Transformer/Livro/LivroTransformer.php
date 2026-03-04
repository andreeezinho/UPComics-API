<?php

namespace App\Http\Transformer\Livro;

use App\Domain\Models\Livro\Livro;

class LivroTransformer {

    public static function transform(Livro $data) : array {
        return [
            'uuid' => $data->uuid,
            'titulo' => $data->titulo,
            'descricao' => $data->descricao,
            'autor' => $data->autor,
            'tipo' => $data->tipo,
            'capa' => $data->capa,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];  
    }

    public static function transformArray(array $users) : array {
        return array_map(function(Livro $data) {
            return self::transform($data);
        }, $users);
    }

}