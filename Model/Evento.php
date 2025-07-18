<?php

App::uses('AppModel', 'Model');

/**
 * Evento Model
 *
 * @property Evento $Evento
 */
class Evento extends AppModel {

    public $name = "Evento";

    public $useTable = "eventos";

    public $primaryKey = "id";
    
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'nome';
    public $actsAs = ['Containable'];

    // The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = [
        'Apoio' => [
            'className' => 'Apoio',
            'foreignKey' => 'evento_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ],
        'Votacao' => [
            'className' => 'Votacao',
            'foreignKey' => 'evento_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ],
        
    ];
}
