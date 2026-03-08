<?php

namespace App\Infra\Persistence\Volume;

use App\Domain\Repositories\Volume\VolumeRepositoryInterface;
use App\Domain\Models\Volume\Volume;
use App\Infra\Persistence\BaseRepository;

class VolumeRepository extends BaseRepository implements VolumeRepositoryInterface {
    
    public static $className = Volume::class;

    public function __construct(){
        parent::__construct();
        $this->model = new Volume();
    }

}
