<?php

namespace App\Infra\Persistence\Livro;

use App\Config\Database;
use App\Infra\Persistence\Traits\CrudTrait;
use App\Infra\Persistence\Traits\FindTrait;
use App\Domain\Repositories\Livro\LivroRepositoryInterface;
use App\Domain\Models\Livro\Livro;
use App\Infra\Services\Log\LogService;

class LivroRepository implements LivroRepositoryInterface {

    const CLASS_NAME = Livro::class;

    use CrudTrait;
    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Livro();
    }

    public function all(array $params){
        return $this->findAll($params);
    }

    public function create(array $data){
        if(empty($data)){
            return null;
        }

        $livro = $this->model->create($data);

        try {
            $create = $this->save($livro);

            if(!$create){
                return null;
            }

            return $this->findBy('uuid', $livro->uuid);
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function update(array $data, int $id){
        if(empty($data)){
            return null;
        }

        $data = $this->model->create($data);

        $livro = $this->findBy('id', $id);

        if(is_null($livro)){
            return null;
        }

        try {
            $update = $this->edit($data, $livro);

            if(!$update){
                return null;
            }

            return $this->findBy('id', $id);
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function delete(int $id){
        if(is_null($this->findBy('id', $id))){
            return false;
        }

        try {
            return $this->destroy($id);
        } catch (\PDOException $e) {
            LogService::logError($e->getMessage());
            return null;
        }
    }

}