<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

	private $usuarioModel;
	public function __construct()
	{
		$this->usuarioModel = new \App\Models\UsuarioModel();
	}

	public function index()
	{

	$data = [
		'titulo'=> 'Listando os usuarios do sistema',
	];

	return view ('Usuarios/index', $data);
	}

	public function recuperaUsuarios(){
		if(!$this->request->isAJAX()){
			return redirect()->back();

		}	

		$atributos =[
			'id',
			'nome',
			'email',
			'ativo',
			'imagem',
		];
		$usuarios =  $this->usuarioModel->select($atributos)
										->orderBy('id','DESC')
										-> findAll();
		$data = [];
		foreach($usuarios as $usuario){

			$data[]=[
				'imagem'=> $usuario->imagem,
				'nome'=> anchor("Usuarios/exibir/$usuario->id", esc($usuario->nome), 'title="Exibir usuario '.esc($usuario->nome).'"'),
				'email'=> esc($usuario->email),
				'ativo'=> ($usuario->ativo==true ? '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo' : '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo'),
			];
			}
			$retorno=[ 
				'data' => $data,
			];
			return $this->response->setJSON($retorno);
		}
		
		public function criar()
		{
			$usuario = new Usuario();
			
			$data = [
				'titulo' => "Criando novo usuario",
				'usuario' => $usuario,
			];
			
			
			return view ('Usuarios/criar', $data);
		}

		public function cadastrar(){

		if(!$this->request->isAJAX()){
			return redirect()->back();

		}
			// Envio o hash do token do form
			$retorno['token'] = csrf_hash();
			
			// Recupero o post da requisicao
			$post = $this->request->getPost();

			// Crio novo objeto da Entidade Usuario
			$usuario = new Usuario($post);
			
			if($this->usuarioModel->protect(false)->save($usuario)){
				
				$btnCriar = anchor("usuarios/criar", 'Cadastrar novo usuario',['class'=> 'btn btn-danger mt-2']);
				
				session()->setFlashdata('sucesso', "Dados salvos com sucesso!<br> $btnCriar");

				//Retornamos o ultimo ID inserido na tabela de usuarios
				// Ou seja, o ID do usuario recem criado
				$retorno['id'] = $this->usuarioModel->getInsertID();

				return $this->response->setJSON($retorno);

			}

			// Retornamos os erros de validação
			$retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
			$retorno['erros_model'] = $this->usuarioModel->errors();


			// Retorno para o ajax request
			return $this->response->setJSON($retorno);
		
		}


		public function exibir(int $id = null)
		{

			$usuario = $this->buscaUsuarioOu404($id);

			
			
			$data=[
				'titulo' => "Detalhando o usuario ".esc($usuario->nome),
				'usuario' => $usuario,
			];
			
			
			return view ('Usuarios/exibir', $data);
		}
		
		public function editar(int $id = null){

			$usuario = $this->buscaUsuarioOu404($id);

			
			
			$data=[
				'titulo' => "Editando o usuario ".esc($usuario->nome),
				'usuario' => $usuario,
			];
			
			
			return view ('Usuarios/editar', $data);
		}

		public function atualizar(){

		if(!$this->request->isAJAX()){
			return redirect()->back();

		}
			// Envio o hash do token do form
			$retorno['token'] = csrf_hash();
			
			// Recupero o post da requisicao
			$post = $this->request->getPost();

			
			
			// Validamos a existencia do usuario
			$usuario = $this->buscaUsuarioOu404($post['id']);

			
			// Se não foi informado a senha, removemos do $post
			// Se não fizermos dessa forma, o hashPassword fará o hash de um string vazia
			if(empty($post['password'])){

			// Esse é um bypass temporario
			unset($post['password']);
			unset($post['password_confirmation']);

			}


			// Preenchemos os atributos do usuario com os valores do post
			$usuario->fill($post);

			if ($usuario->hasChanged() == false) {
				$retorno['info'] = 'Nao a dados para serem atualizados';
				return $this->response->setJSON($retorno);
			}

			if($this->usuarioModel->protect(false)->save($usuario)){

				session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

				return $this->response->setJSON($retorno);

			}

			// Retornamos os erros de validação
			$retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
			$retorno['erros_model'] = $this->usuarioModel->errors();


			// Retorno para o ajax request
			return $this->response->setJSON($retorno);
		
		}
		
		public function editarImagem(int $id = null){

			$usuario = $this->buscaUsuarioOu404($id);

			
			
			$data=[
				'titulo' => "Alterando a imagem do usuario ".esc($usuario->nome),
				'usuario' => $usuario,
			];
			
			
			return view('Usuarios/editar_imagem', $data);
		}


		public function upload()
		{

		if(!$this->request->isAJAX()){
			return redirect()->back();

		}
			// Envio o hash do token do form
			$retorno['token'] = csrf_hash();

			$validacao = service('validation');

			$regras = [
				'imagem'=>'uploaded[imagem]|max_size[imagem,1024]|ext_in[imagem,png,jpg,jpeg,webp]',
			];

			$mensagens = [   // Errors
				'imagem'=> [
				'uploaded'=> 'Por favor escolha uma imagem',
				'ext_in'=> 'Por favor escolha uma imagem png, jpg, jpeg ou webp',
			 ],
        
		];
			$validacao->setRules($regras, $mensagens);
    
			if($validacao->withRequest($this->$request)->run() == false) {
				
			$retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
			$retorno['erros_model'] = $validacao->getErrors();

			
			// Retorno para o ajax request
			return $this->response->setJSON($retorno);
							 
	}
	
	exit;
			
			// Recupero o post da requisicao
			$post = $this->request->getPost();

			
			
			// Validamos a existencia do usuario
			$usuario = $this->buscaUsuarioOu404($post['id']);

			
			// Se não foi informado a senha, removemos do $post
			// Se não fizermos dessa forma, o hashPassword fará o hash de um string vazia
			if(empty($post['password'])){

			// Esse é um bypass temporario
			unset($post['password']);
			unset($post['password_confirmation']);

			}


			// Preenchemos os atributos do usuario com os valores do post
			$usuario->fill($post);

			if ($usuario->hasChanged() == false) {
				$retorno['info'] = 'Nao a dados para serem atualizados';
				return $this->response->setJSON($retorno);
			}

			if($this->usuarioModel->protect(false)->save($usuario)){

				session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

				return $this->response->setJSON($retorno);

			}

			// Retornamos os erros de validação
			$retorno['erro'] = 'Por favor verifique os erros abaixo e tente novamente';
			$retorno['erros_model'] = $this->usuarioModel->errors();


			// Retorno para o ajax request
			return $this->response->setJSON($retorno);
		
		}
		

		/**
		*Metodo que recupera o usuario
		*
		*@param integer $id
		*return Exceptions|object
				
		**/
		private function buscaUsuarioOu404(int $id = null){

			if(!$id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)){

				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Nao encontramos o usuario $id");
			}
			return $usuario;

		}
	}












