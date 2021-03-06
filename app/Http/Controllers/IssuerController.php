<?php

namespace SpedRest\Http\Controllers;

//use Illuminate\Http\Request;

use SpedRest\Http\Request;
use SpedRest\Repositories\IssuerRepository;
use SpedRest\Services\IssuerService;

class IssuerController extends Controller
{
    /**
     * Repository de acesso ao banco de dados
     * @var IssuerRepository
     */
    protected $repository;

    /**
     * Serviço com regras de negócio para acesso a base de dados
     * @var IssuerService
     */
    protected $service;

    protected $userId;

    /**
     * Construtora da classe
     * Instancia o repository
     * @param IssuerRepository $repository
     */
    public function __construct(
        IssuerRepository $repository,
        IssuerService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Lista todos os Emitentes relacionados com o usuário
     * @return Response
     */
    public function index()
    {
        return $this->repository->all();
    }
    
    /**
     * Grava um novo registro de emitente
     * Função exclusiva do admin !
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }
    
    /**
     * Retorna os dados de um emitente
     * Pode ser acessado pelo Admin ou pelo próprio emitente
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        if ($this->checkAutorizations($id) == false) {
            return ['error' => 'Permissões', 'errorDescription' => 'Voce não tem permissão para acessar esses dados.'];
        }
        return $this->repository->find($id);
    }
    
    /**
     * Atualiza dos dados do emitente
     * Pode ser acessado pelo Admin ou pelo próprio emitente
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(), $id);
    }
    
    /**
     * Deleta emitente
     * Função exclusiva do admin !
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        return $this->repository->find($id)->delete();
    }
    
    public function checkAutorizations($id)
    {
        $userId = 1;
        //user ID fornecido pelo OAuth2
        //$userId = Authorizer::getResourceOwnerId();
        return $this->repository->isMember($id, $userId);
    }
}
