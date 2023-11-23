<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
  
    protected $table            = 'usuarios';
    
    protected $returnType       = 'App\Entities\Usuario';
    protected $useSoftDeletes   = true; //Explicas essa caracteristica
    protected $allowedFields    = [
    
    'id',
    'nome',
    'email',
    'password',
    'reset_hash',
    'reset_expira_em',
    'imagem',
    //Não colocaremos o campo ativo... Pois existe a manipulação de formulario
    
    
    
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules    = [
	'id'           => 'permit_empty|is_natural_no_zero', // <-- ESSA LINHA DEVE SER ADICIONADA
	
	// as existentes
	'nome'         => 'required|min_length[3]|max_length[125]',
	'email'        => 'required|valid_email|max_length[230]|is_unique[usuarios.email,id,{id}]', // Nao pode ter espaços
	'password'     => 'required|min_length[6]',
	'password_confirmation' => 'required_with[password]|matches[password]'
    ];

    protected $validationMenssages = [
        'nome'      => [
        'required'      => 'O campo Nome eh obrigatorio.',
        'min_length'    => 'O campo Nome precisa ter pelo menos 3 caracteres.',
        'max_length'    => 'O campo Nome nao pode ser maior que 125 caracteres.',
        ],
        'email'     => [
        'required'      => 'O campo E-mail eh obrigatorio.',
        'max_length'        => 'O campo Nome nao pode ser maior que 230 caracteres.',
        'is_unique'     => 'Esse E-mail ja foi escolhido. Por favor informe outro.',
        ],
        'password_confirmation'     => [
        'required_with'      => 'Por favor confirme a sua senha.',
        'matches'      => 'As senhas precisam combinar',
        
        ],
    ];
   
    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
   
    

     protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
        


        $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        // Removemos dos dados a serem salvos
        unset($data['data']['password']);
        unset($data['data']['password_confirmation']);

        

        } 
        return $data;
    }

    }