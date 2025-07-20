<?php

App::uses('AppController', 'Controller');

/**
 * Apoios Controller
 *
 * @property Apoio $Apoio
 * @property Gt $Gt
 * @property Evento $Evento
 * @property Item $Item
 * @property PaginatorComponent $Paginator
 */
class ApoiosController extends AppController
{

    public $helpers = ['Html', 'Form', 'Text', 'Paginator'];

    /**
     * Components
     *
     * @var array
     */
    public $components = ['Paginator', 'Session'];

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

        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');
        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['ordem' => 'asc']
        ]);

        /** Se evento não veio como parametro nem como cookie então seleciono o último evento */
        if (empty($evento_id)) {
            end($eventos); /** o ponteiro está no último registro */
            $evento_id = key($eventos);
        }

        /** Gravo um cookie com o evento_id */
        $this->Session->write('evento_id', $evento_id);

        $this->Paginator->settings = [
            'Apoio' => [
                'conditions' => ['Apoio.evento_id' => $evento_id],
                'order' => ['Apoio.numero_texto' => 'asc']
            ]
        ];

        $this->set('apoios', $this->Paginator->paginate());
        $this->set('evento_id', $evento_id);
        $this->set('gts', $this->Apoio->find('list', ['fields' => ['Gt.id', 'Gt.sigla'], 'order' => ['Gt.sigla' => 'asc'], 'contain' => ['Gt']]));
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
        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        $options = ['conditions' => ['Apoio.' . $this->Apoio->primaryKey => $id]];
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
        $options = ['conditions' => ['Apoio.' . $this->Apoio->primaryKey => $id]];
        $this->set('apoio', $this->Apoio->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {

        $evento_id = isset($this->request->query['evento_id']) ?  $this->request->query['evento_id'] : $this->Session->read('evento_id');
        $this->loadModel('Evento');
        $eventos = $this->Evento->find(
            'list',
            [
                'fields' => ['id','nome'],
                'order' => ['ordem' => 'asc']
            ]
        );
        if (empty($eventos)) {
            $this->Flash->error(__('Não há eventos cadastrados. Cadastre um evento antes de cadastrar um texto de apoio.'));
            return $this->redirect(['controller' => 'Eventos', 'action' => 'add']);
        }
        if (empty($evento_id)) {
            $evento_id = end($eventos);
        }
        if ($evento_id) {
            /** Envio para o formulário */
            $this->set('evento_id', $evento_id);
        } else {
            $this->Flash->error(__('Não foi possível selecionar o evento. Tente novamente.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {

            /** Verifica se já está cadastrado */
            $this->Apoio->contain();
            $apoio = $this->Apoio->find(
                'first',
                [
                    'conditions' => [
                        'and' =>
                            [
                                'Apoio.numero_texto' => $this->request->data['Apoio']['numero_texto'],
                                'Apoio.evento_id' => $this->request->data['Apoio']['evento_id']
                            ]
                    ]
                ]
            );
            if ($apoio) {
                $this->Flash->error(__('Já há um texto de apio com essa numeração no evento. Verifique e tente novamente.'));
            } else {
                /** Elimina os r e n do texto original */
                $this->request->data['Apoio']['autor'] = str_replace(["<br />"], '', $this->request->data['Apoio']['autor']);
                $this->request->data['Apoio']['texto'] = str_replace(["\r", "\n"], '', $this->request->data['Apoio']['texto']);
                $this->Apoio->create();
                if ($this->Apoio->save($this->request->data)) {
                    $this->Flash->success(__('Texto de apoio inserido.'));
                    return $this->redirect(['action' => 'view', $this->Apoio->getLastInsertId()]);
                } else {
                    $this->Flash->error(__('Não foi possível inserir o texto de apoio. Tente novamente.'));
                }
            }
        }
        $this->set('gts', $this->Apoio->find('list', ['fields' => ['Gt.id', 'Gt.sigla', 'order' => ['Gt.sigla' => 'asc']], 'contain' => ['Gt']]));
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
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        if ($this->request->is(['post', 'put'])) {
            $this->request->data['Apoio']['autor'] = str_replace(["<br />"], ' ', $this->request->data['Apoio']['autor']);
            $this->request->data['Apoio']['texto'] = str_replace(["\r", "\n"], '', $this->request->data['Apoio']['texto']);
            $this->request->data['Apoio']['texto'] = str_replace(["<br />"], ' ', $this->request->data['Apoio']['texto']);
            if ($this->Apoio->save($this->request->data)) {
                $this->Flash->success(__('Texto de apoio atualizado.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o texto de apoio. Tente novamente.'));
            }
        } else {
            $options = ['conditions' => ['Apoio.' . $this->Apoio->primaryKey => $id]];
            $this->request->data = $this->Apoio->find('first', $options);
            $this->set('gts', $this->Apoio->find('list', ['fields' => ['Gt.id', 'Gt.sigla', 'order' => ['Gt.sigla' => 'asc']], 'contain' => ['Gt']]));
            $this->set('eventos', $this->Apoio->find('list', ['fields' => ['Evento.id','Evento.nome', 'order' => ['Evento.ordem' => 'asc']], 'contain' => ['Evento']]));
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

        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Apoio->delete()) {
            $this->Flash->success(__('Texto de apoio excluído.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir o texto de apoio. Tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * collation method
     *
     * @return void
     */
    public function collation()
    {

        $this->autoRender = false;
        $apoios = $this->Apoio->find('all');

        foreach ($apoios as $apoio) {

            $apoionovo['Apoio']['id'] = $apoio['Apoio']['id'];
            $apoionovo['Apoio']['nomedoevento'] = $apoio['Apoio']['nomedoevento'];
            $apoionovo['Apoio']['evento_id'] = $apoio['Apoio']['evento_id'];
            $apoionovo['Apoio']['caderno'] = $apoio['Apoio']['caderno'];
            $apoionovo['Apoio']['numero_texto'] = $apoio['Apoio']['numero_texto'];
            $apoionovo['Apoio']['tema'] = $apoio['Apoio']['tema'];
            $apoionovo['Apoio']['gt'] = $apoio['Apoio']['gt'];
            $apoionovo['Apoio']['gt1'] = $apoio['Apoio']['gt'];
            $apoionovo['Apoio']['titulo'] = isset($apoio['Apoio']['titulo']) ? $apoio['Apoio']['titulo'] : '';
            $apoionovo['Apoio']['titulo1'] = isset($apoio['Apoio']['titulo']) ? $apoio['Apoio']['titulo'] : '';
            $apoionovo['Apoio']['autor'] = str_replace(["\r", "\n"], '', $apoio['Apoio']['autor']);
            $apoionovo['Apoio']['autor1'] = str_replace(["\r", "\n"], '', $apoio['Apoio']['autor']);
            $apoionovo['Apoio']['texto'] = str_replace(["\r", "\n"], '', $apoio['Apoio']['texto']);
            $apoionovo['Apoio']['texto1'] = str_replace(["\r", "\n"], '', $apoio['Apoio']['texto']);

            if ($this->Apoio->save($apoionovo, ['validate' => false])) {
            } else {
                // $log = $this->Apoio->getDataSource()->getLog(false, false);
                // debug($log);
                $errors = $this->Apoio->invalidFields();
                // pr($errors);
                // pr($this->Apoio->validationErrors);
                $this->Flash->error(__('Não foi possível atualizar o texto de apoio ' . $apoio['Apoio']['id'] . '. Tente novamente.'));
                // die();
            }
        }
    }
}
