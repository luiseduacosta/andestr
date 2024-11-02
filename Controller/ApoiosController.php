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

    public $helpers = ['Html', 'Form', 'Text'];

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

        /* Se evento não veio como parametro nem como cookie então seleciono o último evento */
        if (empty($evento_id)):
            if (empty($evento_id)) {
                end($eventos); // o ponteiro está no último registro
                $evento_id = key($eventos);
            }
        endif;
        if (isset($evento_id)):
            /** Gravo um cookie com o evento_id */
            $this->Session->write('evento_id', $evento_id);
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
        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__('Texto de apoio não encontrado'));
        }
        // $tr = isset($this->request->query['tr']) ? $this->request->query['tr'] : null;
        // $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');
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
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');

        $eventos = $this->Apoio->Evento->find(
            'list',
            [
                'fields' => ['nome'],
                'order' => ['ordem']
            ]
        );

        if (empty($evento_id)) {
            $evento_id = end($eventos);
        }

        if ($evento_id) {
            /** Envio para o formulário */
            $this->set('evento_id', $evento_id);
        }

        if ($this->request->is('post')) {

            // pr($this->request->data);
            // die();
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
            if ($apoio):
                $this->Flash->error(__('Já há um texto de apio com essa numeração no evento. Verifique e tente novamente.'));
            else:
                /** Elimina os r e n do texto original */
                $this->request->data['Apoio']['texto'] = str_replace(["\r", "\n"], '', $this->request->data['Apoio']['texto']);
                $this->Apoio->create();
                if ($this->Apoio->save($this->request->data)) {
                    $this->Flash->success(__('Texto de apoio inserido.'));
                    return $this->redirect(['action' => 'view', $this->Apoio->getLastInsertId()]);
                } else {
                    $this->Flash->error(__('Não foi possível inserir o texto de apoio. Tente novamente.'));
                }
            endif;
        }
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
        if ($this->request->is(['post', 'put'])) {
            $this->request->data['Apoio']['texto'] = str_replace(["\r", "\n"], '', $this->request->data['Apoio']['texto']);
            $this->request->data['Apoio']['texto'] = str_replace(["<br />"], ' ', $this->request->data['Apoio']['texto']);
            if ($this->Apoio->save($this->request->data)) {
                $this->Flash->success(__('Registro atualizado.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('Não foi possível atualizar. Tente novamente.'));
            }
        } else {
            $options = ['conditions' => ['Apoio.' . $this->Apoio->primaryKey => $id]];
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
        return $this->redirect(['action' => 'index']);
    }

    public function collation()
    {

        $this->autoRender = false;
        $apoios = $this->Apoio->find('all');

        foreach ($apoios as $apoio) {

            // pr($apoio['Apoio']['id']);
            // pr($apoio['Apoio']['gt']);
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
            // pr($apoionovo['Apoio']['gt']);

            if ($this->Apoio->save($apoionovo, ['validate' => false])) {
                // $this->Flash->success(__('Texto de apoio atualizado.'));
            } else {
                $log = $this->Apoio->getDataSource()->getLog(false, false);
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
