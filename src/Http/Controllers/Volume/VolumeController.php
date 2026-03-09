<?php

namespace App\Http\Controllers\Volume;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\Volume\VolumeRepositoryInterface;
use App\Domain\Repositories\Livro\LivroRepositoryInterface;
use App\Http\Transformer\Volume\VolumeTransformer;
use App\Infra\Services\File\FileService;

class VolumeController extends Controller {

    protected $volumeRepository;
    protected $livroRepository;
    protected $fileService;

    public function __construct(VolumeRepositoryInterface $volumeRepository, LivroRepositoryInterface $livroRepository, FileService $fileService){
        parent::__construct();
        $this->volumeRepository = $volumeRepository;
        $this->livroRepository = $livroRepository;
        $this->fileService = $fileService;
    }

    public function index(Request $request){
        $params = $request->all();

        $volumes = $this->volumeRepository->all($params);

        return $this->respJson([
            'message' => 'Volumes listados',
            'data' => VolumeTransformer::transformArray($volumes)
        ]);
    }

    public function store(Request $request, string $livroUuid){
        if(is_null($livro = $this->livroRepository->findBy('uuid', $livroUuid))){
            return $this->respJson([
                'message' => 'Livro do volume não encontrado'
            ], 422);
        }
        
        $data = $request->all();

        $validate = $this->validate($data, [
            'volume' => 'required|int',
            'paginas' => 'required|int',
            'path' => 'required',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $file = $this->fileService->uploadFile($data['path'], '/content/books', 'pdf');

        if(is_null($file)){
            return $this->respJson([
                'message' => 'Não foi possível salvar o arquivo'
            ], 500);
        }

        $data['path'] = $file['hash_name'];
        $data = array_merge($data, ['livros_id' => $livro->id]);

        $create = $this->volumeRepository->create($data);

        if(is_null($create)){
            return $this->respJson([
                'message' => 'Erro ao criar o volume'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Volume criado com sucesso',
            'data' => VolumeTransformer::transform($create)
        ], 201);
    }

    public function update(Request $request, string $livroUuid, string $uuid){
        if(is_null($this->livroRepository->findBy('uuid', $livroUuid))){
            return $this->respJson([
                'message' => 'Livro do volume não encontrado'
            ], 422);
        }

        if(is_null($volume = $this->livroRepository->findBy('uuid', $uuid))){
            return $this->respJson([
                'message' => 'Volume não encontrado'
            ], 422);
        }
        
        $data = $request->all();

        $validate = $this->validate($data, [
            'volume' => 'required|int',
            'paginas' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $update = $this->volumeRepository->update($data, $volume->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao editar o volume'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Volume atualizado com sucesso',
            'data' => VolumeTransformer::transform($update)
        ], 201);
    }

    public function updateFile(Request $request, string $livroUuid, string $uuid){
        if(is_null($this->livroRepository->findBy('uuid', $livroUuid))){
            return $this->respJson([
                'message' => 'Livro do volume não encontrado'
            ], 422);
        }

        if(is_null($volume = $this->livroRepository->findBy('uuid', $uuid))){
            return $this->respJson([
                'message' => 'Volume não encontrado'
            ], 422);
        }
        
        $data = $request->all();

        $validate = $this->validate($data, [
            'path' => 'required'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $file = $this->fileService->uploadFile($data['path'], '/content/books', 'pdf');

        if(is_null($file)){
            return $this->respJson([
                'message' => 'Não foi possível salvar o arquivo'
            ], 500);
        }

        $update = $this->volumeRepository->update(['path' => $file['hash_name']], $volume->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao atualizar arquivo do volume'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Arquivo atualizado com sucesso'
        ], 201);
    }

    public function destroy(Request $request, $livroUuid, $uuid){
        if(is_null($this->livroRepository->findBy('uuid', $livroUuid))){
            return $this->respJson([
                'message' => 'Livro do volume não encontrado'
            ], 422);
        }

        if(is_null($volume = $this->livroRepository->findBy('uuid', $uuid))){
            return $this->respJson([
                'message' => 'Volume não encontrado'
            ], 422);
        }

        $delete = $this->volumeRepository->delete($volume->id);

        if(!$delete){
            return $this->respJson([
                'message' => 'Erro ao excluir volume'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao excluir volume'
        ], 201);
    }

}