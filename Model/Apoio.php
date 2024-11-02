<?php

App::uses('AppModel', 'Model');

/**
 * Apoio Model
 *
 */
class Apoio extends AppModel
{

    public $name = "Apoio";

    public $useTable = "apoios";

    public $primaryKey = "id";

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'titulo';
    public $actsAs = ['Containable'];
    public $belongsTo = ['Evento'];
    public $hasMany = ['Item'];
    public $validate = array(
        'caderno' => [
            'rule' => ['inList', ['Principal', 'Anexo']],
            'message' => 'Digite Principal ou Anexo.'
        ],
        'tema' => [
            'rule' => ['inList', ['I', 'II', 'III', 'IV']]
        ],
        'gt' => [
            'rule' => [
                'inList',
                [
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
                    'Outras'
                ]
            ],
            'required' => FALSE,
            'allowEmpty' => TRUE,
        ]
    );
}
