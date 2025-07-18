<?php

App::uses('AppModel', 'Model');

/**
 * Apoio Model
 * 
 * @property Apoio $Apoio
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
    public $belongsTo = ['Evento', 'Gt'];
    public $hasMany = ['Item'];

    public $validate = [
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
            'message' => 'Selecione a Comissão ou o GT.'
        ]
    ];
}
