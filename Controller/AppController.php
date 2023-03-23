<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Flash',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'Items',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'Items',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
            'authError' => 'Ação não autorizada.',
            'loginError' => 'Login errado. Tente novamente.',
            'authorize' => array('Controller')
        ),
        'DebugKit.Toolbar',
    );

    public function isAuthorized($user) {
        // Admin can access every action

        if (isset($user['role']) && $user['role'] === 'admin') {
            return TRUE;
        }

        // Default deny
        return false;
    }

    public function beforeFilter() {

        $this->Auth->allow('index', 'view', 'apoiocompleto', 'relatorio');
        // $this->Auth->allow('login');
        // debug($this->Auth->user());
        // die();
        $this->set('usuario', $this->Auth->user());
    }

    public function autenticausuario() {

        if ($this->Auth->user('id')):
            $this->loadModel('User');
            $usuario = $this->User->find('first', [
                'conditions' => ['User.id' => $this->Auth->user('id')]
            ]);
            // pr($usuario);
            if (!empty($usuario)):
                if ($usuario['User']['role'] == 'editor' || $usuario['User']['role'] == 'admin'):
                    $grupo = NULL;
                    $papel = $usuario['User']['role'];
                else:
                    $grupo = substr($usuario['User']['username'], 5, 2);
                    $papel = $usuario['User']['role'];
                endif;
                $usuario = ['grupo' => $grupo, 'role' => $papel];
            endif;
        // pr($usuario);
        // die();
        // $this->set('usuario', $usuario);
        // return $usuario;
        endif;
    }

}
