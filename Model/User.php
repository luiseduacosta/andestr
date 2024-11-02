<?php

// app/Model/User.php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 * @property Votacao $Votacao
 */

class User extends AppModel
{

    public $displayField = 'username';
    public $actsAs = ['Containable'];
    public $hasMany = [
        'Votacao' => [
            'className' => 'Votacao',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ]
    ];
    public $validate = array(
        'username' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'O nome do usuário é necessário'
            ]
        ],
        'password' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'O password é obrigatório'
            ]
        ],
        'role' => array(
            'valid' => [
                'rule' => ['inList', ['relator', 'editor', 'admin']],
                'message' => 'Insira um papel válido ',
                'allowEmpty' => false
            ]
        )
    );

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

}

?>