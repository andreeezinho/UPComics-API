<?php

namespace App\Infra\Persistence\User;

use App\Config\Database;
use App\Domain\Models\User\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\Traits\CrudTrait;
use App\Infra\Persistence\Traits\FindTrait;
use App\Infra\Services\Log\LogService;

class UserRepository implements UserRepositoryInterface {

    const CLASS_NAME = User::class;

    use CrudTrait;
    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new User();
    }

    public function login(string $email, string $senha){
        $sql = "SELECT * FROM 
                {$this->model->getTable()}
            WHERE
                email = :email
            AND
                ativo = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
        $user = $stmt->fetch();

        if($user && password_verify($senha, $user->senha)){
            unset($user->id);
            unset($user->senha);
            return $user;
        }

        return null;
    }

    public function all(array $params = []){
        return $this->findAll($params);
    }

    public function create(array $data){
        if(empty($data)){
            return null;
        }

        if(empty($data['senha'])){
            $data['senha'] = 'senha123';
        }

        $user = $this->model->create($data);

        if(empty($user->icone)){
            $user->icone = 'default.png';
        }

        try {
            $create = $this->save($user);

            if(!$create){
                return null;
            }

            return $this->findBy('uuid', $user->uuid);

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

        $user = $this->findBy('id', $id);

        if(is_null($user)){
            return null;
        }

        try {
            $update = $this->edit($data, $user);

            if(!$update){
                return null;
            }

            return $this->findBy('id', $id);;
            
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function updateIcone(array $data, int $id){
        if(empty($data)){
            return null;
        }

        $data = $this->model->create($data);

        $user = $this->findBy('id', $id);

        if(is_null($user)){
            return null;
        }
        
        try {
            $update = $this->edit($data, $user);

            if(!$update){
                return null;
            }

            return $this->findBy('id', $id);;
            
        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

    public function updateSenha(array $data, int $id){
        if(empty($data)){
            return null;
        }

        $data = $this->model->create($data);

        $user = $this->findBy('id', $id);

        if(is_null($user)){
            return null;
        }

        try {
            $update = $this->edit($data, $user);

            if(!$update){
                return null;
            }

            return $this->findBy('id', $id);;
            
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