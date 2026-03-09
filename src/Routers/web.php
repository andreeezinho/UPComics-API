<?php

use App\Config\Router;
use App\Config\Auth;
use App\Config\Container;
use App\Config\DependencyProvider;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RecuperarSenha\RecuperarSenhaController;
use App\Http\Controllers\Livro\LivroController;
use App\Http\Controllers\Volume\VolumeController;

$router = new Router();
$auth = new Auth();
$container = new Container();
$dependencyProvider = new DependencyProvider($container);
$dependencyProvider->register();

$authController = $container->get(AuthController::class);
$userController = $container->get(UserController::class);
$recuperarSenhaController = $container->get(RecuperarSenhaController::class);
$livroController = $container->get(LivroController::class);
$volumeController = $container->get(VolumeController::class);

// - Rotas

//autenticacao
$router->create("POST", "/auth", [$authController, 'login']);
$router->create("POST", "/google-auth", [$authController, 'loginWithGoogle']);
$router->create("GET", "/google-link", [$authController, 'generateGoogleAuthLink']);
$router->create("GET", "/me", [$authController, 'profile'], $auth);

//usuarios
$router->create("GET", "/usuarios", [$userController, 'index'], $auth);
$router->create("POST", "/usuarios", [$userController, 'store'], $auth);
$router->create("PUT", "/usuarios/{uuid}", [$userController, 'update'], $auth);
$router->create("PATCH", "/usuarios/{uuid}/password", [$userController, 'updatePassword'], $auth);
$router->create("POST", "/usuarios/{uuid}/icon", [$userController, 'updateIcon'], $auth);
$router->create("DELETE", "/usuarios/{uuid}", [$userController, 'destroy'], $auth);

//recuperar-senha
$router->create("POST", "/recuperar-senha/enviar-codigo", [$recuperarSenhaController, 'sendVerificationCode']);
$router->create("PUT", "/recuperar-senha", [$recuperarSenhaController, 'changePassword']);

//livros
$router->create("GET", "/livros", [$livroController, 'index'], $auth);
$router->create("POST", "/livros", [$livroController, 'store'], $auth);
$router->create("PUT", "/livros/{uuid}", [$livroController, 'update'], $auth);
$router->create("POST", "/livros/{uuid}/cover", [$livroController, 'updateCover']);
$router->create("DELETE", "/livros/{uuid}", [$livroController, 'destroy'], $auth);

//volumes
$router->create("GET", "/volumes", [$volumeController, 'index'], $auth);
$router->create("POST", "/volumes/{livro_uuid}", [$volumeController, 'store'], $auth);
$router->create("PUT", "/volumes/{livro_uuid}/{uuid}", [$volumeController, 'update'], $auth);
$router->create("POST", "/volumes/{livro_uuid}/{uuid}/file", [$volumeController, 'updateFile'], $auth);
$router->create("DELETE", "/volumes/{livro_uuid}/{uuid}", [$volumeController, 'destroy'], $auth);

return $router;