<?php

App::uses('AppModel', 'Model');

/**
 * Votacao Model
 * 
 * @property Votacao $Votacao
 *
 */
class Votacao extends AppModel {

    public $name = "Votacao";

    public $useTable = "votacoes";

    public $primaryKey = "id";
    
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'resultado';
    public $actsAs = array('Containable');
    public $belongsTo = ['Evento', 'Item', 'User'];
    public $validate = array(
        'grupo' => array(
            'rule' => '/^\d{1,2}$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite o número do grupo (1 ou 2 carateres numéricos).'
        ),
        'tr' => array(
            'rule' => '/^\d{1,2}$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite um ou dois carateres numéricos'
        ),
        'resultado' => array(
            'rule' => 'notBlank',
            'required' => TRUE,
            'allowEmpty' => FALSE,
        ),
        'votacao' => array(
            'rule' => '/^\d{1,2}\/\d{1,2}\/\d{1,2}$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite a votação nesta ordem: favoráveis / contrários / abstenções'
        )
    );

    public function isOwnedBy($votacao, $user) {
        // die($votacao);
        return $this->field('id', ['id' => $votacao, 'user_id' => $user]) !== false;
    }

}
