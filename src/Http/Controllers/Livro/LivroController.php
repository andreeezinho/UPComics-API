<?php

namespace App\Http\Controllers\Livro;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\Livro\LivroRepositoryInterface;
use App\Http\Transformer\Livro\LivroTransformer;
use App\Infra\Services\File\FileService;

class LivroController extends Controller {

    protected $livroRepository;
    protected $fileService;

    public function __construct(LivroRepositoryInterface $livroRepository, FileService $fileService){
        parent::__construct();
        $this->livroRepository = $livroRepository;
        $this->fileService = $fileService;
    }

    public function index(Request $request){
        $params = $request->all();

        $livros = $this->livroRepository->all($params);
    
        return $this->respJson([
            'message' => 'Livros encontrados',
            'data' => LivroTransformer::transformArray($livros)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'titulo' => 'required|string',
            'descricao' => 'string',
            'autor' => 'required|string',
            'tipo' => 'required',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $create = $this->livroRepository->create($data);

        if(is_null($create)){
            return $this->respJson([
                'message' => 'Erro ao cadastrar usuário'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => LivroTransformer::transform($create)
        ], 201);
    }

    public function update(Request $request, string $uuid){
        $livro = $this->livroRepository->findBy('uuid', $uuid);

        if(is_null($livro)){
            return $this->respJson([
                'message' => 'Livro não encontrado'
            ], 422);
        }

        $data = $request->all();

        $validate = $this->validate($data, [
            'titulo' => 'required|string',
            'descricao' => 'string',
            'autor' => 'required|string',
            'tipo' => 'required',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $update = $this->livroRepository->update($data, $livro->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao editar livro'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao editar livro',
            'data' => LivroTransformer::transform($update)
        ], 201);
    }

    public function updateCover(Request $request, string $uuid){
        $livro = $this->livroRepository->findBy('uuid', $uuid);

        if(is_null($livro)){
            return $this->respJson([
                'message' => 'Livro não encontrado'
            ], 422);
        }

        $data = $request->getFileParams();

        $validate = $this->validate($data, [
            'capa' => 'required'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $saveFile = $this->fileService->uploadFile($data['capa'], '/img/covers');

        if(is_null($saveFile)){
            return $this->respJson([
                'message' => 'Não foi possível salvar arquivo'
            ], 500);
        }

        $update = $this->livroRepository->update(['capa' => $saveFile['hash_name']], $livro->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao editar capa do livro'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao editar capa do livro',
            'data' => LivroTransformer::transform($update)
        ], 201);
    }

    public function destroy(Request $request, string $uuid){
        $livro = $this->livroRepository->findBy('uuid', $uuid);

        if(is_null($livro)){
            return $this->respJson([
                'message' => 'Livro não encontrado'
            ], 422);
        }

        $delete = $this->livroRepository->delete($livro->id);

        if(!$delete){
            return $this->respJson([
                'message' => 'Erro ao excluir livro'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao excluir livro'
        ], 201);
    }

}