<?php

App::uses('AppController', 'Controller');

/**
 * Items Controller
 */
class ItemsController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $components = array('Paginator', 'Session');

    public function isAuthorized($user)
    {

        if (isset($user['role']) && $user['role'] == 'editor') {
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

    public function index()
    {
        $apoio_id = isset($this->request->query['apoio_id']) ? $this->request->query['apoio_id'] : null;
        $tr = isset($this->request->query['tr']) ? $this->request->query['tr'] : null;
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');

        /** Para fazer a lista dos eventos */
        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['ordem']
        ]);

        /** Se evento_id não veio como parametro nem pode ser calculado a partir do apoio_id então seleciono o último evento */
        if (empty($evento_id)):
            end($eventos); // o ponteiro está no último registro
            $evento_id = key($eventos);
        endif;

        /** Gravo o cookei com o evento_id */
        if ($evento_id) {
            $this->Session->write('evento_id', $evento_id);
        }

        $this->loadModel('Apoio');
        if (isset($this->request->query['tr']) && isset($evento_id)):
            $tr = $this->request->query['tr'];
            $this->Paginator->settings = [
                'Item' => [
                    'conditions' => ['Apoio.evento_id' => $evento_id, 'Item.tr' => $tr],
                    'order' => ['item' => 'asc']
                ]
            ];
        else:
            $this->Paginator->settings = [
                'Item' => [
                    'conditions' => ['Apoio.evento_id' => $evento_id],
                    'order' => ['item' => 'asc']
                ]
            ];
        endif;

        $this->set('items', $this->Paginator->paginate());

        /** Para fazer a lista das TRs na coluna lateral */
        $tresolucao = $this->Apoio->Item->find('all', [
            'conditions' => ['Apoio.evento_id' => $evento_id],
            'fields' => ['tr'],
            'group' => ['tr']
        ]);

        $this->set('tr', $tresolucao);
        $this->set('evento_id', $evento_id);
        $this->set('eventos', $eventos);
    }

    public function add()
    {
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');

        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['id' => 'asc']
        ]);

        if (empty($evento_id)):
            end($eventos); // o ponteiro está no último registro
            $evento_id = key($eventos);
        endif;

        /** Não acontece nunca? */
        if (empty($evento_id)) {
            $this->Flash->error(__('Sem indicação de evento'));
            return $this->redirect(['controller' => 'items', 'action' => 'index']);
            // echo "Erro";
        }
        // pr($evento_id);
        // die();
        /** Localiza se há TRs */
        $this->loadModel('Apoios');
        $apoios = $this->Apoios->find('all', [
            'conditions' => ['Apoios.evento_id' => $evento_id],
            ['order' => ['numero_texto' => 'desc']]
        ]);
        $apoioslista = $this->Apoios->find('list');
        // pr($apoios);
        /** Para aumentar a numeração dos items da TR */
        if ($apoios) {
            $ultimo = end($apoios);
            $ultimo_tr = $ultimo['Apoios']['numero_texto'];
            if (strlen($ultimo_tr) == 1) {
                $ultimo_tr = '0' . $ultimo_tr;
            }
            $items = $this->Item->find('all', [
                'conditions' => ['apoio_id' => $ultimo['Apoios']['id']]
            ]);
            $ultimo_item = end($items);
            /** Dividir o item e aumente em + 1 para o próximo */
            if ($ultimo_item) {
                $ultimoItem = $ultimo_item['Item']['item'];
                $itemparcela = explode('.', $ultimoItem);
                $itemparcela_tr = $itemparcela[0] + 1;
                $itemparcela_item = $itemparcela[1] + 1;
                if (strlen($itemparcela_tr) == 1) {
                    $itemparcela_tr = '0' . $itemparcela_tr;
                } else {
                    $itemparcela_tr;
                }
                if (strlen($itemparcela_item) == 1) {
                    $itemparcela_item = '0' . $itemparcela_item;
                } else {
                    $itemparcela_item;
                }
                // pr($itemparcela_tr);
                // pr($itemparcela_item);
            }

        } else {
            $itemparcela_tr = '01';
            $itemparcela_item = '01';
        }
        /** Envio para o formulário */
        $this->set('ultimo_tr', isset($ultimo_tr) ? $ultimo_tr : '01');
        $this->set('item_item', isset($itemparcela_item) ? $itemparcela_item : '01');
        $this->set('apoio_id', $ultimo['Apoios']['id']);
        $this->set('apoios', $apoioslista);

        if ($this->request->is('post')) {
            // debug($this->request);
            // A partir do Tr busco o id na tabela Resolucao
            if ($this->request->data['Item']['apoio_id']) {

                $verifica_tr = $this->Item->Apoio->find(
                    'first',
                    ['conditions' => ['Apoio.id' => $this->request->data['Item']['apoio_id']]]
                );
                // pr($verifica_tr);
                // $log = $this->Item->getDataSource()->getLog(false, false);
                // debug($log);
                /**
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
                $this->Flash->success(__('Item inserido.'));
                return $this->redirect(array('controller' => 'Items', 'action' => 'view', $this->Item->getLastInsertId()));
            } else {
                $this->Flash->error(__('Item não foi inserido. Tente novamente.'));
            }
        }
        // pr($evento_id);
        // die();
        $tr = $this->Item->Apoio->find(
            'list',
            [
                'fields' => ['numero_texto'],
                'conditions' => ['Apoio.evento_id' => $evento_id]
            ]
        );

        // $log = $this->Item->Apoio->getDataSource()->getLog(false, false);
        // debug($log);
        // pr($tr);
        // die();

        if (!isset($tr)) {
            $this->Flash->error(__('Não há textos de resolução cadastrados!'));
            return $this->redirect(['controller' => 'Apoios', 'action' => 'index']);
            // die("Erro: Não há textos de resolução cadastrados!");
        }

        $this->set('tr', $tr);
        $this->set('eventos', $eventos);
        $this->set('evento_id', $evento_id);
    }

    public function view($id = null)
    {

        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__('Invalid resolucao'));
        }

        if ($this->Auth->user('id')):
            $categoria = $this->autenticausuario();
        endif;

        $votacao = isset($this->request->query['votacao']) ? $this->request->query['votacao'] : 0;
        $this->set('votacao', $votacao);

        $options = array('conditions' => ['Item.' . $this->Item->primaryKey => $id]);
        $this->set('item', $this->Item->find('first', $options));
    }

    public function edit($id = null)
    {
        // debug($this->request->data);
        // die();
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__('Item inválido'));
        }
        if ($this->request->is(array('post', 'put'))) {

            if ($this->Item->save($this->request->data)) {
                $this->Flash->success(__('Item atualizado.'));
                return $this->redirect(array('controller' => 'Items', 'action' => 'view', $this->request->data['Item']['id']));
            } else {
                $this->Flash->error(__('Item não foi inserido. Tente novamente.'));
            }
        } else {
            $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
            $this->request->data = $this->Item->find('first', $options);
        }

        $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
        $resolucaos = $this->Item->find('first', $options);
        // pr($resolucaos);
        // die();
        $this->set('resolucaos', $resolucaos);
    }

    /*
     * Método que atualiza o campo apoio_id da tabela utilizando o TR (numero_texto)
     */

    public function atualiza()
    {

        $items = $this->Item->find('all');

        // pr($items);
        // die();

        foreach ($items as $c_item):

            // pr($c_item);
            // pr($c_item['Item']['tr']);
            // echo ltrim(substr($c_item['Item']['item'], 0, 2), 0);
            // die();

            $this->loadModel('Apoio');
            // $apoio = $this->Apoio->find('first', ['evento_id'])
            $resultado = $this->Apoio->find('first', ['conditions' => ['Apoio.id' => $c_item['Item']['apoio_id']]]);

            // pr($resultado);
            // echo $resultado['Apoio']['id'];
            // die();
            // $c_item['Item']['apoio_id'] = $resultado['Resolucao']['apoio_id'];
            // $c_item['Item']['tr'] = $resultado['Apoio']['numero_texto'];
            if (empty($c_item['Item']['item'])) {
                if ($resultado['Apoio']['numero_texto']) {
                    $c_item['Item']['item'] = $resultado['Apoio']['numero_texto'] . '.00.00';
                } else {
                    echo "Sem número de texto";
                    die();
                }
            }
            // pr($c_item['Item']['item']);
            $c_item['Item']['apoio_id'] = $resultado['Apoio']['id'];
            $c_item['Item']['texto1'] = $c_item['Item']['texto'];
            // pr($c_item);
            // die();

            if ($this->Item->save($c_item['Item'])) {

                $this->Flash->success(__('Item atualizado.'));
            } else {
                debug($this->Item->validationErrors);
                // die();
                // $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }

        endforeach;
    }

    public function delete($id = null)
    {
        $this->Item->id = $id;
        if (!$this->Item->exists()) {
            throw new NotFoundException(__('Item inválido'));
        }

        // Capturo o valor do campo resolucao_id para ir para a TR do item
        $resolucao = $this->Item->findById($id);

        $this->request->allowMethod('post', 'delete');

        if ($this->Item->delete()) {
            $this->Flash->success(__('Item excluído.'));
        } else {
            $this->Flash->error(__('Tente novamente.'));
        }
        return $this->redirect(['controller' => 'items', 'action' => 'view', '?' => ['apoio_id' => $resolucao['Item']['apoio_id']]]);
    }

    public function seleciona_lista()
    {

        $items = $this->Item->find('list', array('fields' => array('id', 'item', 'texto')));
        // pr($items);
        // die();
        $this->set('items', $items);
    }

}
