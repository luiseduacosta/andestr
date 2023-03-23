<?php

App::uses('AppModel', 'Model');

/**
 * Apoio Model
 *
 */
class Votacao extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'resultado';
    public $actsAs = array('Containable');
    public $belongsTo = ['Evento', 'Item'];
    public $validate = array(
        'grupo' => array(
            'rule' => '/^\d{1,2}$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite o número do grupo (1 ou 2 carateres numéricos).'
        ),
        'tr' => array(
            'rule' => '/^\d{2}$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite dois carateres numéricos'
        ),
        'item' => array(
            'rule' => '/^\d{2}.\d{2}(.\d{2})?$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite primeiro dois carateres numéricos da TR, "." e a seguir dois carateres númericos do item. Assim: nn.nn.nn'
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
        return $this->field('id', array('id' => $votacao, 'user_id' => $user)) !== false;
    }

}
