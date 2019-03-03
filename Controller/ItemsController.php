<?php

App::uses('AppController', 'Controller');

/**
 * Items Controller
 */
class ItemsController extends AppController {

    /**
     * Scaffold
     *
     * @var mixed
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

        $usuario = $this->autenticausuario();
    }

    public function index() {

        if (isset($this->params['named']['tr'])):
            $tr = $this->params['named']['tr'];
            $this->Item->recursive = 2;
            $this->set('items', $this->Paginator->paginate('Item', array('Item.tr' => $tr)));
        else:
            $this->Item->recursive = 2;
            $this->set('items', $this->Paginator->paginate());
        endif;

        $tr = $this->Item->query('select DISTINCT tr as tr FROM items order by tr');

        $this->set('tr', $tr);
    }

    public function add() {

        // debug($this->request);
        // die();
        if ($this->request->is('post')) {
            // debug($this->request);
            // A partir do Tr busco o id na tabela Resolucao
            if ($this->request->data['Item']['apoio_id']) {

                $verifica_tr = $this->Item->Apoio->find('first', array('conditions' => array('Apoio.id' => $this->request->data['Item']['apoio_id'])));
                // pr($verifica_tr);
                // $log = $this->Item->getDataSource()->getLog(false, false);
                // debug($log);
                // echo $verifica_tr['Apoio']['numero_texto'] . " " . substr($this->request->data['Item']['item'], 0, 2);
                // die;
                /*
                 * Verifica que o item e o Tr estejam coordenados
                 */
                if ($verifica_tr):
                    if ($verifica_tr['Apoio']['numero_texto'] != substr($this->request->data['Item']['item'], 0, 2)):
                        // echo $verifica_tr['Apoio']['numero_texto'] . " " . substr($this->request->data['Item']['item'], 0, 2);
                        $this->Flash->error(__('Os dois primeiros números do item devem corresponder com o TR'));
                        return $this->redirect(array('controller' => 'items', 'action' => 'add'));
                    else:
                        $this->request->data['Item']['tr'] = $verifica_tr['Apoio']['numero_texto'];
                    endif;
                else:
                    $this->Flash->error(__('O Texto de Apoio do item não existe. Insira uma nova TR começando pelo texto de apoio'));
                    return $this->redirect(array('controller' => 'apoios', 'action' => 'add'));
                endif;
            }

            // pr($this->request->data);
            // die();

            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                // pr($this->request->data);
                // die();
                $this->Flash->success(__('The item has been saved.'));
                return $this->redirect(array('controller' => 'Items', 'action' => 'view/' . $this->Item->getLastInsertId()));
            } else {
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
        $this->set('tr', $tr = $this->Item->Apoio->find('list', array('fields' => array('Apoio.numero_texto'))));
    }

    public function view($id = null) {

        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__('Invalid resolucao'));
        }

        if ($this->Auth->user('id')):
            $grupo = $this->autenticausuario();
        endif;

        if (isset($this->params['named']['votacao'])):
            $votacao = $this->params['named']['votacao'];
            $this->set('votacao', $votacao);
        else:
            $votacao = 0;
            $this->set('votacao', $votacao);
        endif;

        $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
        $this->set('item', $this->Item->find('first', $options));
    }

    public function edit($id = null) {
        // debug($this->request->data);
        // die();
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__('Invalid item'));
        }
        if ($this->request->is(array('post', 'put'))) {

            // A partir do TR busco o Id na tabela Resolucao para prencher o campo resolucao_id
            if ($this->request->data['Item']['outrotr']) {

                // A partir do TR busco o id na tabela Apoio para prencher o campo apoio_id
                $this->loadModel('Apoio');
                $outro_apoio = $this->Apoio->find('first', array(
                    'conditions' => array('Apoio.numero_texto = ' . $this->request->data['Item']['outrotr']
                )));
                // pr($outro_apoio['Apoio']['id']);
                $this->request->data['Item']['apoio_id'] = $outro_apoio['Resolucao']['id'];
                // pr($this->request->data);
                // die();
            }

            if ($this->Item->save($this->request->data)) {
                $this->Flash->success(__('Item inserido.'));
                // return $this->redirect(array('controller' => 'Items', 'action' => 'view/' . $this->request->data['Item']['resolucao_id']));
            } else {
                $this->Flash->error(__('Item não foi inserido. Tente novamente.'));
            }
        } else {
            $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
            $this->request->data = $this->Item->find('first', $options);
        }

        $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
        $resolucaos = $this->Item->find('first', $options);
        $this->set('resolucaos', $resolucaos);

        // pr($resolucaos);
    }

    /*
     * Método que atualiza o campo apoio_id da tabela utilizando o TR (numero_texto)
     */

    public function atualiza() {

        $items = $this->Item->find('all');

        // pr($items); 
        // die();

        foreach ($items as $c_item):

            // pr($c_item['Item']['tr']);
            // echo ltrim(substr($c_item['Item']['item'], 0, 2), 0);
            // die();

            $this->loadModel('Apoio');
            $resultado = $this->Apoio->find('first', array('conditions' => array('Apoio.numero_texto' => ltrim(substr($c_item['Item']['item'], 0, 2)), 0)));

            // pr($resultado);
            // echo $resultado['Apoio']['id'];
            // die();
            $c_item['Item']['apoio_id'] = $resultado['Resolucao']['apoio_id'];
            $c_item['Item']['tr'] = $resultado['Apoio']['numero_texto'];

            $c_item['Item']['apoio_id'] = $resultado['Apoio']['id'];
            // pr($c_item);
            // die();

            if ($this->Item->save($c_item['Item'])) {

                $this->Flash->success(__('The item has been saved.'));
            } else {
                debug($this->Item->validationErrors);
                die();
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }

        endforeach;
    }

    public function delete($id = null) {
        $this->Item->id = $id;
        if (!$this->Item->exists()) {
            throw new NotFoundException(__('Invalid item'));
        }

        // Capturo o valor do campo resolucao_id para ir para a TR do item
        $id_resolucao = $this->Item->findById($id);

        $this->request->allowMethod('post', 'delete');

        if ($this->Item->delete()) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('controller' => 'Item', 'action' => 'index'));
    }

    public function seleciona_lista() {

        $items = $this->Item->find('list', array('fields' => array('id', 'item', 'texto')));
        // pr($items);
        // die();
        $this->set('items', $items);
    }

}
