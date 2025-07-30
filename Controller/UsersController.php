<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 * @property User $User
 */

class UsersController extends AppController {

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = ['Session', 'Html', 'Form', 'Text'];

    /**
     * Components
     *
     * @var array
     */
    public $components = ['Paginator', 'Session'];

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('login', 'logout');
        $this->set('usuario', $this->Auth->user());
        
    }

     /**
     * index method
     *
     * @return void
     */   
    public function index() {
        
        $this->User->contain();
        $this->set('users', $this->paginate());
    }

    /**
     * view method
     *
     * @param string|null $id User id.
     * @return void
     * @throws NotFoundException When user does not exist.
     */
    public function view($id = null) {

        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Usuário não localizado'));
        }
        $options = ['conditions' => ['User.' . $this->User->primaryKey => $id]];
        $this->set('user', $this->User->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {

        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('Usuário inserido!'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(
                    __('Não foi possível inserir o novo usuário. Tente novamente.')
            );
        }
    }

    /**
     * edit method
     *
     * @param string|null $id User id.
     * @return void
     * @throws NotFoundException When user does not exist.
     */
    public function edit($id = null) {
        
        if ($this->Auth->user('role') == 'editor' || $this->Auth->user('role') == 'admin'):

            $this->set('usuario', $this->Auth->user());
        
            if (!$this->User->exists($id)) {
                throw new NotFoundException(__('Usuário não localizado'));
            }
            if ($this->request->is(['post', 'put'])) {
                if ($this->User->save($this->request->data)) {
                    $this->Flash->success(__('Usuário atualizado'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(
                        __('Não foi possível atualizar o registro do usuário. Tente novamente.')
                );
            } else {
                $options = ['conditions' => ['User.' . $this->User->primaryKey => $id]];
                $this->request->data = $this->User->find('first', $options);
                unset($this->request->data['User']['password']);
            }
        else:
            $this->Flash->error(__('Usuário não autorizado'));
            return $this->redirect(['action' => 'login']);
        endif;
    }

    /**
     * delete method
     *
     * @param string|null $id User id.
     * @return void
     * @throws NotFoundException When user does not exist.
     */
    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Usuário não localizado'));
        }
        $this->request->allowMethod('post', 'delete');

        if ($this->User->delete()) {
            $this->Flash->success(__('Usuário excluído!'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Não foi possível excluir o usuário. Tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * login method
     *
     * @return void
     */
    public function login() {

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Dados incorretos, tente novamente'));
        }
    }

    /**
     * logout method
     *
     * @return void
     */
    public function logout() {

        $this->Session->delete('evento_id');
        return $this->redirect($this->Auth->logout());
    }

}