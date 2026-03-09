<?php

namespace App\Http\Transformer\Volume;

use App\Domain\Models\Volume\Volume;

class VolumeTransformer {

    public static function transform(Volume $data) : array {
        return [
            'uuid' => $data->uuid,
            'volume' => $data->volume,
            'paginas' => $data->paginas,
            'path' => $data->path,
            'ativo' => $data->ativo,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ];
    }

    public static function transformArray(array $volumes) : array {
        return array_map(function(Volume $data) {
            return self::transform($data);
        }, $volumes);
    }

}