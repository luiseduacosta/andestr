<?php

App::uses('AppModel', 'Model');

/**
 * Evento Model
 *
 * @property Apoio $Apoio
 */
class Evento extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'evento';
    public $actsAs = array('Containable');

    // The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Apoio' => array(
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
        ),
        'Votacao' => array(
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
        ),
        
    );
}
