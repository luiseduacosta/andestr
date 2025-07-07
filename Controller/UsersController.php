<?php

// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $helpers = ['Html', 'Form', 'Text'];

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

    /*
      public function isAuthorized($usuario) {

      if (isset($usuario['role']) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')) {
      return true;
      }

      // All registered users can view
      if ($this->action == 'index') {
      return true;
      }

      return parent::isAuthorized($usuario);
      }
     */

    public function index() {
        
        $this->User->contain();
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {

        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Usuário não localizado'));
        }
        $options = ['conditions' => ['User.' . $this->User->primaryKey => $id]];
        $this->set('user', $this->User->find('first', $options));
    }

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

    public function login() {

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Dados incorretos, tente novamente'));
        }
    }

    public function logout() {

        $this->Session->delete('evento_id');
        return $this->redirect($this->Auth->logout());
    }

}