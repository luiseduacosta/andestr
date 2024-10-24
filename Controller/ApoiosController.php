<?php

App::uses('AppController', 'Controller');

/**
 * Apoios Controller
 *
 * @property Apoio $Apoio
 * @property PaginatorComponent $Paginator
 */
class ApoiosController extends AppController
{

    public $helpers = array('Html', 'Form', 'Text');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function isAuthorized($user)
    {

        if (isset($user['role']) && $user['role'] === 'editor') {
            return true;
        }

        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }

        return parent::isAuthorized($user);
    }

    function beforeFilter()
    {
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
    public function index()
    {

        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : null;

        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['ordem' => 'asc']
        ]);

        /* Se evento não veio como parametro então seleciono o último evento */
        if (empty($evento_id)):
            end($eventos); // o ponteiro está no último registro
            $evento_id = key($eventos);
        endif;

        if (isset($evento_id)):
            $this->Paginator->settings = [
                'Apoio' => [
                    'conditions' => ['Apoio.evento_id' => $evento_id],
                    'order' => ['Apoio.numero_texto' => 'asc']
                ]
            ];
        endif;

        $this->set('apoios', $this->Paginator->paginate());
        $this->set('evento_id', $evento_id);
        $this->set('eventos', $eventos);
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {

        $tr = $this->request->query('tr');
        $evento = $this->request->query('evento');

        if (isset($tr) && !empty($tr)) {
            $idquery = $this->Apoio->find(
                'first',
                [
                    'conditions' => ['numero_texto' => $tr, 'evento_id' => $evento],
                    'fields' => ['id']
                ]
            );
            // pr($idquery);
            if ($idquery) {
                $id = $idquery['Apoio']['id'];
                // pr($id);
            }
        }

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
    public function apoiocompleto($id = null)
    {

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
    public function add()
    {
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : null;
        if ($evento_id) {
            $this->set('evento_id', $evento_id);
        }

        if ($this->request->is('post')) {

            /** Verifica se já está cadastrado */
            $this->Apoio->contain();
            $apoio = $this->Apoio->find(
                'first',
                [
                    'conditions' =>
                        [
                            'Apoio.numero_texto' => $this->request->data['Apoio']['numero_texto'],
                            'Apoio.evento_id' => $this->request->data['Apoio']['evento_id']
                        ]
                ]
            );
            if ($apoio):
                $this->Flash->error(__('Já há um texto de apio com essa numeração no evento. Verifique e tente novamente.'));
            else:
                $this->Apoio->create();
                if ($this->Apoio->save($this->request->data)) {
                    $this->Flash->success(__('Texto de apoio inserido.'));
                    return $this->redirect(['action' => 'view', $this->Apoio->getLastInsertId()]);
                } else {
                    $this->Flash->error(__('Não foi possível inserir o texto de apoio. Tente novamente.'));
                }
            endif;
        }
        $eventos = $this->Apoio->Evento->find(
            'list',
            ['fields' => ['nome'],
            'order' => ['nome']]
        );
        $this->set('eventos', $eventos);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {

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
            $this->set('eventos', $this->Apoio->Evento->find('list', ['fields' => 'nome']));
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {

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
