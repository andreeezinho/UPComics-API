<?php

namespace App\Infra\Persistence\Livro;

use App\Domain\Repositories\Livro\LivroRepositoryInterface;
use App\Domain\Models\Livro\Livro;
use App\Infra\Persistence\BaseRepository;

class LivroRepository extends BaseRepository implements LivroRepositoryInterface {

    public static $className = Livro::class;

    public function __construct(){
        parent::__construct();
        $this->model = new Livro();
    }

}