<?php

namespace App\Infra\Persistence\RecuperarSenha;

use App\Config\Database;
use App\Domain\Models\RecuperarSenha\RecuperarSenha;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\Traits\CrudTrait;
use App\Infra\Persistence\Traits\FindTrait;
use App\Infra\Services\Log\LogService;

class RecuperarSenhaRepository implements RecuperarSenhaRepositoryInterface {

    const CLASS_NAME = RecuperarSenhaRepository::class;

    use CrudTrait;
    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new RecuperarSenha();
    }

    public function create(array $data){
        if(empty($data)){
            return null;
        }

        $recoveryPassword = $this->model->create($data);

        try {
            $create = $this->save($recoveryPassword);

            if(!$create){
                return null;
            }

            return $this->findBy('uuid', $recoveryPassword->uuid);

        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function verifyCode(int $code, int $usuarios_id) : bool {
        $find = $this->findBy('usuarios_id', $usuarios_id);

        if(time() > strtotime($find->expires_at)){
            return false;
        }

        if($code !== $find->codigo){
            return false;
        }

        return true;
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