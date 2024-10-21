<?php

App::uses('AppModel', 'Model');

/**
 * Apoio Model
 *
 */
class Apoio extends AppModel {

    public $name = "Apoio";

    public $useTable = "apoios";

    public $primaryKey = "id";
    
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'tema';
    public $actsAs = ['Containable'];
    public $belongsTo = ['Evento'];
    public $hasMany = ['Item'];
    public $validate = array(
        'caderno' => array(
            'rule' => array('inList', array('Principal', 'Anexo')),
            'message' => 'Digite Principal ou Anexo.'
        ),
        'tema' => array(
            'rule' => array('inList', array('I', 'II', 'III', 'IV'))
        ),
        'gt' => array(
            'rule' => array('inList', array(
                    'Federais',
                    'Estaduais',
                    'GTCQERGDS',
                    'GTCA',
                    'GTC',
                    'GTCT',
                    'GT Fundações',
                    'GTHMD',
                    'GTPAUA',
                    'GTPE',
                    'GTPFS',
                    'GTSSA',
                    'GT Verbas',
                    'Comissão da Verdade',
                    'Tesouraria',
                    'Secretaria',
                    'Outras')),
            'required' => FALSE,
            'allowEmpty' => TRUE,
        )
    );

}
