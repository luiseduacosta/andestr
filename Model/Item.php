<?php

App::uses('AppModel', 'Model');

/**
 * Item Model
 *
 */
class Item extends AppModel {

    public $name = "Item";

    public $useTable = "items";

    public $primaryKey = "id";
    
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'item';
    public $actsAs = array('Containable');    
    // public $belongsTo = array('Resolucao');
    public $belongsTo = array('Apoio');
    
    public $hasMany = array('Votacao');

    public $validate = array(
        'tr' => array(
            'rule' => 'numeric',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite o número de TR'
        ),

        'item' => array(
            'rule' => '/^\d{2}.\d{2}(.\d{2})?(.\d{2})?$/i',
            'required' => TRUE,
            'allowEmpty' => FALSE,
            'message' => 'Digite primeiro dois carateres numéricos da TR, ".", e dois carateres númericos do item e, se for necessário mais dois carateres como subitem. Assim: nn.nn.nn.nn'
        )
    );

}
