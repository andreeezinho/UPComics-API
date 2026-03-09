<?php

namespace App\Config;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\User\UserRepository;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Infra\Persistence\RecuperarSenha\RecuperarSenhaRepository;
use App\Domain\Repositories\Livro\LivroRepositoryInterface;
use App\Infra\Persistence\Livro\LivroRepository;
use App\Domain\Repositories\Volume\VolumeRepositoryInterface;
use App\Infra\Persistence\Volume\VolumeRepository;

class DependencyProvider {

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function register(){

        $this->container
            ->set(
                UserRepositoryInterface::class,
                new UserRepository()
            );

        $this->container
            ->set(
                RecuperarSenhaRepositoryInterface::class,
                new RecuperarSenhaRepository()
            );

        $this->container
            ->set(
                LivroRepositoryInterface::class,
                new LivroRepository()
            );
        
        $this->container
            ->set(
                VolumeRepositoryInterface::class,
                new VolumeRepository()
            );

    }

}