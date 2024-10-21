<?php

App::uses('AppController', 'Controller');

/**
 * Eventos Controller
 *
 * @property Evento $Evento
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class EventosController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session', 'Flash');

    function beforeFilter() {
        parent::beforeFilter();

        $usuario = $this->Auth->user();
        if (isset($usuario) && $usuario['role'] == 'relator'):
            if (strlen($usuario['username']) == 6):
                $usuariogrupo = substr($usuario['username'], 5, 1);
            elseif (strlen($usuario['username']) == 7):
                $usuariogrupo = substr($usuario['username'], 5, 2);
            endif;
            $this->set('usuariogrupo', $usuariogrupo);
        endif;
        $this->set('usuario', $usuario);
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        
        $this->Evento->contain(['Apoio'=>'Item']);
        // $this->Evento->recursive = 0;
        $this->set('eventos', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {

        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__('Invalid evento'));
        }
        $this->Evento->contain(['Apoio']);
        $options = array('conditions' => array('Evento.' . $this->Evento->primaryKey => $id));
        $this->set('evento', $this->Evento->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Evento->create();
            if ($this->Evento->save($this->request->data)) {
                $this->Flash->success(__('Evento criado.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('Tente novamente.'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__('Invalid evento'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Evento->save($this->request->data)) {
                $this->Flash->success(__('Evento atualizado.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('Evento não foi atualizado. Tente novamente.'));
            }
        } else {
            $options = array('conditions' => array('Evento.' . $this->Evento->primaryKey => $id));
            $this->request->data = $this->Evento->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__('Invalid evento'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Evento->delete($id)) {
            $this->Flash->success(__('Evento excluído.'));
        } else {
            $this->Flash->error(__('Evento não foi excluído. Tente novamente.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
