<?php
App::uses('AppModel', 'Model');
/**
 * Gt Model
 *
 */
class Gt extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nome';
	public $actsAs = ['Containable'];
    public $hasMany = ['Apoio'];

	public $order = ['Gt.sigla' => 'asc'];

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sigla' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'nome' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'outras' => array(
			'blank' => array(
				'rule' => array('blank'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
