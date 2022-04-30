<?php

App::uses('AppController', 'Controller');

/**
 * Apoios Controller
 *
 * @property Apoio $Apoio
 * @property PaginatorComponent $Paginator
 */
class ApoiosController extends AppController {

    public $helpers = array('Html', 'Form', 'Text');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function isAuthorized($user) {

        if (isset($user['role']) && $user['role'] === 'editor') {
            return true;
        }

        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }

        return parent::isAuthorized($user);
    }

    function beforeFilter() {
        parent::beforeFilter();

        if ($this->Auth->user('id')):
            $usuario = $this->autenticausuario();
        endif;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {

        if (isset($this->params['named']['tema'])):
            $tema = $this->params['named']['tema'];
            $this->Apoio->recursive = 0;
            $this->set('apoios', $this->Paginator->paginate('Apoio', array('Apoio.tema' => $tema)));
        else:
            $this->Apoio->recursive = 0;
            $this->set('apoios', $this->Paginator->paginate());
        endif;
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {

        $tr = $this->request->query('tr');
        // pr($tr);
        if (isset($tr) && !empty($tr)) {
            $idquery = $this->Apoio->find('first', ['conditions' => ['numero_texto' => $tr], 'fields' => ['id']]);
            // pr($idquery);
            if ($idquery) {
                $id = $idquery['Apoio']['id'];
                // pr($id);
            }
        }
        // echo $id;
        // die();
        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        $options = array('conditions' => array('Apoio.' . $this->Apoio->primaryKey => $id));
        $this->set('apoio', $this->Apoio->find('first', $options));
    }

    /**
     * apoiocompleto method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function apoiocompleto($id = null) {

        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        $options = array('conditions' => array('Apoio.' . $this->Apoio->primaryKey => $id));
        $this->set('apoio', $this->Apoio->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {

        if ($this->request->is('post')) {

            // pr($this->request->data);
            $apoio = $this->Apoio->find('first', array('conditions' => array('Apoio.numero_texto' => $this->request->data['Apoio']['numero_texto'])));
            // pr($apoio);
            if ($apoio):
                $this->Flash->error(__('Já há um texto de apio com essa numeração. Verifique e tente novamente.'));
            else:
                $this->Apoio->create();
                if ($this->Apoio->save($this->request->data)) {
                    $this->Flash->success(__('Texto de apoio inserido.'));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->error(__('Não foi possível inserir o texto de apoio . Tente novamente.'));
                }
            endif;
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

        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Invalid apoio'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Apoio->save($this->request->data)) {
                $this->Flash->success(__('Registro atualizado.'));
                return $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Flash->error(__('Não foi possível atualizar. Tente novamente.'));
            }
        } else {
            $options = array('conditions' => array('Apoio.' . $this->Apoio->primaryKey => $id));
            $this->request->data = $this->Apoio->find('first', $options);
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

        $this->Apoio->id = $id;
        if (!$this->Apoio->exists()) {
            throw new NotFoundException(__('Invalid apoio'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Apoio->delete()) {
            $this->Flash->success(__('The apoio has been deleted.'));
        } else {
            $this->Flash->error(__('The apoio could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
