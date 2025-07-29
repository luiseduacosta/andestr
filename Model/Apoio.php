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
        'numero_texto' => [
            'rule' => ['numeric', 'notBlank'],
            'message' => 'Digite o número do texto.'
        ],
        'gt_id' => [
            'rule' => ['notBlank'],
            'message' => 'Selecione o GT ou Setor.'
        ],
        'autor' => [
            'rule' => ['notBlank'],
            'message' => 'Digite o autor.'
        ],
        'titulo' => [
            'rule' => ['notBlank'],
            'message' => 'Digite o título.'
        ],
        'texto' => [
            'rule' => ['notBlank'],
            'message' => 'Digite o texto.'        
        ],
    ];
}
