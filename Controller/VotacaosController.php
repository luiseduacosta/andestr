<?php

/**
 * App Controller
 */
App::uses('AppController', 'Controller');

/**
 * Votacaos Controller
 */
class VotacaosController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $components = ['Paginator', 'Session'];
    public $helpers = ['Html', 'Form', 'Text'];

    function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow('add', 'edit', 'delete', 'relatorio');

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

    public function index($id = NULL)
    {

        $grupo = isset($this->request->query['grupo']) ? $this->request->query['grupo'] : null;
        $item = isset($this->request->query['item']) ? $this->request->query['item'] : null;
        $tr = isset($this->request->query['tr']) ? $this->request->query['tr'] : null;
        $item_id = isset($this->request->query['item_id']) ? $this->request->query['item_id'] : null;
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');

        /** Faço a lista dos eventos */
        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['ordem' => 'asc']
        ]);
        if (empty($evento_id)):
            $evento_id = end($eventos);
        endif;
        if ($evento_id) {
            $this->Session->write('evento_id', $evento_id);
        }

        $this->set('eventos', $eventos);
        $this->set('evento_id', $evento_id);

        $this->Votacao->contain();

        if ($grupo and $item_id and $tr and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.item_id' => $item_id,
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $item and $evento_id) { // relator
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.item' => $item,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $tr and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($item_id and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.item_id' => $item_id,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($tr and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($item and $evento_id) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.item' => $item,
                'Votacao.evento_id' => $evento_id
            ]));
        } else {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.evento_id' => $evento_id
            ]));
        }

        $this->set('grupos', $this->Votacao->find('all', [
            'conditions' => ['Votacao.evento_id' => $evento_id],
            'fields' => ['DISTINCT Votacao.grupo as grupo '],
            'order' => ['Votacao.grupo ASC']
        ]));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = NULL)
    {
        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Votação não localizada'));
        }

        if ($this->Auth->user('role') == 'editor') {
            $this->Flash->error(__('Editor não pode atualizar votações.'));
            return $this->redirect(['action' => 'view', $this->request->data['Votacao']['id']]);
        }

        /** Executa a ação */
        if ($this->request->is(['post', 'put'])) {
            /** Ajusto o valor do item_id em função do valor do item */
            if ($this->request->data['Votacao']['id']) {
                $item_id = $this->Votacao->find('first', [
                    'conditions' => [
                        'Votacao.id ' => $this->request->data['Votacao']['id']
                    ]
                ]);
                if (!empty($item_id['Votacao']['id'])) {
                    $this->request->data['Votacao']['item_id'] = $item_id['Votacao']['item_id'];
                } else {
                    $this->Flash->error(__('Votação sem item_id'));
                    return $this->redirect(['action' => 'view', $this->request->data['Votacao']['id']]);
                }
            }

            /** O tr tem que ter dois dígitos */
            $this->request->data['Votacao']['tr'] = strlen($this->request->data['Votacao']['tr']) == 1 ? '0' . $this->request->data['Votacao']['tr'] : $this->request->data['Votacao']['tr'];

            /** Se é uma inclusão atualiza o texto do item */
            if ($this->request->data['Votacao']['resultado'] == 'inclusão') {

                if ($this->request->data['Votacao']['item_id']) {
                    $this->loadModel('Item');
                    $item = $this->Item->find('first', [
                        'conditions' => ['Item.id' => $this->request->data['Votacao']['item_id']]
                    ]);
                    $item['Item']['texto'] = $this->request->data['Votacao']['item_modificada'];
                    if ($this->Item->save($item)) {
                        $this->Flash->success(__('Item atualizada'));
                    }
                } else {
                    $this->Flash->error(__('Inclusão não inserida na tabela Items'));
                }
            }

            /** Verifica se os dois primeiros dígitos do item correspondem com a TR na votação */
            if (substr($this->request->data['Votacao']['item'], 0, 2) != $this->request->data['Votacao']['tr']) {
                $this->Flash->error(__('Os dois primeiros dígitos do campo Item tem que ser iguais ao TR.'));
                return $this->redirect(['action' => 'edit', $this->request->data['Votacao']['id']]);
            }

            /** Verifica votação por si for minoritária */
            if (isset($this->request->data['Votacao']['votacao'])) {
                $votos = explode('/', $this->request->data['Votacao']['votacao']);
            }
            $totalvotos = $votos[0] + $votos[1] + $votos[2];
            $tercovotos = $totalvotos / 3;
            if ($votos[1] >= $tercovotos) {
                $this->Flash->error(__('Há uma votação minoritária. Não esqueça de registrar essa votação minoritária em observações ou num novo item minoritário.'));
            }

            if ($this->Votacao->save($this->request->data)):
                $this->Flash->success(__('Votação atualizada.'));
                return $this->redirect(['action' => 'view', $this->request->data['Votacao']['id']]);
            else:
                pr($this->Votacao->validationErrors);
                $this->Flash->error(__('Votação não foi atualizada. Tente novamente.'));
            endif;
        }
        $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
        $this->request->data = $this->Votacao->find('first', $options);
    }


    /** Cuidado com esta função que altera  o user_id da tabela Votacao */
    public function atualizausuario()
    {

        $grupos = $this->Votacao->find('all', [
            'order' => ['grupo']
        ]);

        foreach ($grupos as $c_grupos):
            $this->loadModel('User');
            $busca = 'grupo' . $c_grupos['Votacao']['grupo'];
            $usuario = $this->User->find('all', [
                'conditions' => [
                    'User.username' => $busca
                ]
            ]);
            if (!empty($usuario['User']['id'])):

                echo "Votação sem usuario de grupo?";
                echo ' Id ' . $c_grupos['Votacao']['id'];
                echo " ";
                echo ' grupo: ' . $c_grupos['Votacao']['grupo'];
                echo " ";
                echo $usuario['User']['username'];
                echo " usuário id ";
                echo $usuario['User']['id'];
                echo "<br>";

            endif;
            $this->Votacao->query('update votacaos set user_id = ' . $usuario[0]['User']['id'] . ' where id = ' . $c_grupos['Votacao']['id']);

        endforeach;
    }

    /** Id eh o item_id em votação */
    public function add($id = NULL)
    {
        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');
        $item_id = isset($this->request->query['item_id']) ? $this->request->query['item_id'] : null;
        $votacao_id = isset($this->request->query['votacao_id']) ? $this->request->query['votacao_id'] : null;
        $resultado = isset($this->request->query['resultado']) ? $this->request->query['resultado'] : null;

        if ($votacao_id) {
            // pr($evento_id);
            // pr($item_id);
            // pr($votacao_id);
            // pr($resultado);
            // die();
        }
        
        /* Se o resultado é minoritária, então seta a flag com o valor 0 para que não entre em loop */
        if (isset($resultado) && $resultado == 'minoritária') {
            $this->Session->write('flagminoritaria', 0);
        }

        /** Envio o item da votação para o add da view*/
        if (isset($item_id) && $item_id != null) {
            $this->loadModel('Item');
            $this->Item->contain(['Apoio']);
            $options = ['conditions' => ['Item.' . $this->Item->primaryKey => $item_id]];
            $this->set('item', $this->Item->find('first', $options));
        } else { 
            $this->Flash->error(__('Selecione o item a ser votado.'));
            return $this->redirect(['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $evento_id]]);
        }

        $this->set('usuario', $this->Auth->user());

        if (isset($resultado) && $resultado == 'minoritária'):
            // Se eh uma votacao minoritaria obtenho a votacao realizada para recuperar os resultados
            if (isset($this->request->query['votacao_id'])) {
                $votacao_id = $this->request->query['votacao_id'];
            }

            if ($votacao_id):
                $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $votacao_id]];
                $this->Votacao->contain();
                $votacao = $this->Votacao->find('first', $options);
                $votacao['Votacao']['resultado'] = 'minoritária';
                $this->set('votacao', $votacao);
            else:
                $this->Flash->error(__('Sem votação anterior do item.'));
                // echo "Error: Sem votação anterior" . "<br>";
                exit;
            endif;
        endif;

        if ($this->request->is('post')) {

            /** Exepcionalmente se a votação é do usuario admin */
            if ($this->Auth->user('role') == 'admin'):
                $this->request->data['Votacao']['user_id'] = $this->Auth->user('id');
                /** Teria que limitar ao relator? */
            else:
                $this->request->data['Votacao']['user_id'] = $this->Auth->user('id');
            endif;

            /** O item_modificado somente se o resultado foi modificada */
            if ($this->request->data['Votacao']['resultado'] <> 'modificada') {
                $this->request->data['Votacao']['item_modificada'] = null;
            }

            /** Trasfero o valor de item_incluida e item_minoritaria para item_modificada somente se está vazia e excluo item_incluida e item_minoritaria */
            if (empty($this->request->data['Votacao']['item_modificada'])) {
                if ($this->request->data['Votacao']['item_incluida']) {
                    $this->request->data['Votacao']['item_modificada'] = $this->request->data['Votacao']['item_incluida'];
                }

                if ($this->request->data['Votacao']['item_minoritaria']) {
                    $this->request->data['Votacao']['item_modificada'] = $this->request->data['Votacao']['item_minoritaria'];
                }
                unset($this->request->data['Votacao']['item_incluida']);
                unset($this->request->data['Votacao']['item_minoritaria']);
            }

            /* Calculo se eh minoritaria excluindo quando já sei que é minoritário pelo resultado */
            if ($resultado <> 'minoritária') {
                if (isset($this->request->data['Votacao']['votacao'])):
                    $votos = explode('/', $this->request->data['Votacao']['votacao']);
                endif;
                $totalvotos = $votos[0] + $votos[1] + $votos[2];
                $tercovotos = $totalvotos / 3;
                if ($votos[1] >= $tercovotos) {
                    // echo "Há uma votação minoritária. Não esqueça de registrar essa votação minoritária em observações ou num novo item minoritário." . "<br>";
                    $this->Session->write('flagminoritaria', 1);
                } else {
                    $this->Session->write('flagminoritaria', 0);
                }
            }

            /** Busca se já foi votado o item pelo grupo e avisa no Flash. Exepto quando é uma inclusão ou minoritária */
            if (($this->request->data['Votacao']['resultado'] == 'inclusão') || ($this->request->data['Votacao']['resultado'] == 'minoritária')) {
                // echo "modifica ou outros resultados" . '<br>';
                // die();
            } else {
                $javotado = $this->Votacao->find('first', [
                    'conditions' => [
                        'Votacao.item_id' => $this->request->data['Votacao']['item_id'],
                        'Votacao.grupo' => $this->request->data['Votacao']['grupo'],
                        'Votacao.evento_id' => $evento_id,
                        'Votacao.resultado IN' => ['aprovada', 'modificada', 'suprimida', 'remitida']
                    ]
                ]);

                if ($javotado) {
                    $this->Flash->error(__("Item já votado pelo grupo."));
                    // return $this->redirect(['controller' => 'votacaos', 'action' => 'view', $javotado['Votacao']['id']]);
                }
            }

            /** Verifica se os dois primeiros dígitos do item correspondem com a TR na votação */
            if (substr($this->request->data['Votacao']['item'], 0, 2) != $this->request->data['Votacao']['tr']) {
                $this->Flash->error(__('Os dois primeiros dígitos do campo Item tem que ser iguais ao TR.'));
                return $this->redirect(['action' => 'add', $id]);
            }

            /** Function suprime TR na sua totalidade */
            /* Quando selecionado 1 => Sim: cria um registo de supresão para cada item da TR. */
            if ($this->request->data['Votacao']['tr_suprimida'] == 1):
                $this->suprimeTR($this->request->data['Votacao']['tr_suprimida']);
            endif;

            /** Function aprovaembloco */
            /* Quando selecionado 1 => Sim: cria um registo de aprovação para cada item da TR excluindo os items que já foram votados. */
            if ($this->request->data['Votacao']['tr_aprovada'] == 1):
                $this->aprovaembloco($this->request->data);
            endif;

            if ($this->request->data['Votacao']['resultado'] == 'modificada'):
                if (empty($this->request->data['Votacao']['item_modificada'])):
                    $this->Flash->error(__("Registre a alteração do item da TR."));
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'add', $id]);
                endif;
            endif;

            /** Se é uma inclusão de um novo item tenho que criar o registro na tabela Items */
            if ($this->request->data['Votacao']['resultado'] == 'inclusão') {
                // die('inclusão');
                $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['tr'] . '.99';

                $this->loadModel('Item');
                $this->Item->contain(['Apoio']);
                $item_id = $this->Item->find('first', [
                    'conditions' => [
                        'and' =>
                            [
                                'Item.id' => $this->request->data['Votacao']['item_id'],
                                'Apoio.evento_id' => $evento_id
                            ]
                    ]
                ]);

                $item['Item']['apoio_id'] = $item_id['Item']['apoio_id'];
                $item['Item']['tr'] = $this->request->data['Votacao']['tr'];
                $item['Item']['item'] = $this->request->data['Votacao']['item'];
                $item['Item']['texto'] = $this->request->data['Votacao']['item_modificada'];

                $this->Item->create();
                if ($this->Item->save($item)):
                    echo "Item novo inserido";
                endif;

                /* Altero o valor do item_id com o id do item inserido */
                $this->request->data['Votacao']['item_id'] = $this->Item->id;
            }

            /** Verifica que a votação seja inserida utilizando como separador dos votos uma barra inclinada */
            $verificaponto = strpos(($this->request->data['Votacao']['votacao']), ".");
            if ($verificaponto) {
                // echo "Separar os valores com uma barra inclinada: '/'" . "<br>";
                $this->Flash->error(__("Separar os valores da votação com uma barra inclinada: '/'"));
            }
            $verificabarra = strpos(($this->request->data['Votacao']['votacao']), "/");
            if (empty($verificabarra)) {
                // echo "Separar os valores com uma barra inclinada: '/'" . "<br>";
                $this->Flash->error(__("Separar os valores da votação com uma barra inclinada: '/'"));
            }

            /** Quando a TR é votada como remitida ou como outra tem que ter um texto em observações explicando */
            if ($this->request->data['Votacao']['resultado'] == 'remitida' || $this->request->data['Votacao']['resultado'] == 'outra') {
                if (empty($this->request->data['Votacao']['observacoes'])) {
                    $this->Flash->error(__("Especifique no campo 'Observações' o resultado da deliberação do item 'remitido' ou votado como 'outra'."));
                    return $this->redirect(['controller' => 'Items', 'action' => 'view', $item_id]);
                }
            }

            if ($this->request->data['Votacao']['resultado'] == 'aprovada') {
                $this->request->data['Votacao']['item_modificada'] = null;
            }

            /** Finalmente insiro a votação do item. Quando minoritária retorna para adicionar a votação minoritária */
            $this->Votacao->create();
            if ($this->Votacao->save($this->request->data)) {
                $flagminoritaria = $this->Session->read('flagminoritaria');
                if ($flagminoritaria == '1') {
                    $this->Flash->success(__('Votação inserida. Registre a votação minoritária'));
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'add', '?' => ['item_id' => $this->request->data['Votacao']['item_id'], 'votacao_id' => $this->Votacao->getLastInsertID(), 'resultado' => 'minoritária']]);
                } else {
                    // $this->Flash->success(__('Votação inserida.'));
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'view', $this->Votacao->getLastInsertID()]);
                }
            } else {
                $errors = $this->Votacao->validationErrors;
                // pr($errors);
                // die();
                $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
            }
        }
    }

    /**
     * itemId method
     *
     * @param string $dados
     * @return void
     */
    public function itemId($dados)
    {

        if ($dados) {
            /* Votação de inclusao de item novo: inclusao. O campo item_id fica em 0 */
            $items = explode(".", $dados);

            /* Atribui 0 ao item_id e 99 ao item */
            if ($this->request->data['Votacao']['resultado'] == 'inclusão') {
                $this->request->data['Votacao']['item_id'] = 0;
                $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['tr'] . '.99';
            } else {
                /* Capturo o valor do id da tabela Item para inserir no campo item_id da tabela Votacao */
                $this->loadModel('Item');
                $this->Item->contain();
                $outro_item = $this->Item->find('first', [
                    'conditions' => [
                        'Item.item' => $this->request->data['Votacao']['item']
                    ]
                ]);
                if (!empty($outro_item['Item']['id'])) {
                    // Votação de aprovação, modificação, supresão, minoritária, remissão, outro. O campo item_id eh capturado da tabela Item
                    $this->request->data['Votacao']['item_id'] = $outro_item['Item']['id'];
                } else {
                    $this->Flash->error(__('O item não existe. Inserir novo item na TR'));
                    return $this->redirect(array('controller' => 'Items', 'action' => 'add'));
                }
            }
        }
        return $this->request->data['Votacao']['item_id'];
    }

    /** Aprova todos os items da TR em bloco */
    public function aprovaembloco($dados)
    {

        $evento_id = $this->Session->read('evento_id');
        if ($this->request->data['Votacao']['tr_aprovada'] == 1) {
            $this->loadModel('Item');
            $this->Item->contain(['Apoio']);
            $items = $this->Item->find(
                'all',
                [
                    'conditions' => [
                        'and' => [
                            'Item.tr' => $this->request->data['Votacao']['tr'],
                            'Apoio.evento_id' => $evento_id
                        ]
                    ]
                ]
            );

            foreach ($items as $c_item) {
                $this->request->data['Votacao']['item'] = substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item_id'] = $c_item['Item']['id'];
                /** Verifico se já foi votado */
                $javotado = $this->Votacao->find('first', [
                    'conditions' => [
                        'and' => [
                            'Votacao.item' => $this->request->data['Votacao']['item'],
                            'Votacao.grupo' => $this->request->data['Votacao']['grupo'],
                            'Votacao.evento_id' => $evento_id
                        ]
                    ]
                ]);

                /** Se não foi votado o item então insiro os valores de aprovação */
                if (sizeof($javotado) == 0) {
                    $this->Votacao->create();
                    if ($this->Votacao->save($this->request->data)) {
                        $this->Flash->success(__('Votação inserida.'));
                    } else {
                        $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
                    }
                } else {
                    $this->Flash->error(__('Item já foi votado pelo grupo.'));
                }
            }
            return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
            // die();
        }
    }

    public function minoritaria($dados)
    {

        $votos = explode('/', $dados);

        $totalvotos = $votos[0] + $votos[1] + $votos[2];
        $tercovotos = $totalvotos / 3;

        $minoritariavotos = NULL;
        if ($votos[1] >= $tercovotos) {
            if ($this->Session->read('flagminoritaria') == 1) {
                $this->Session->write('flagminoritaria', 0);
                $minoritariavotos = 0;
            } elseif ($this->Session->read('flagminoritaria') == 0) {
                $this->Session->write('flagminoritaria', 1);
                $minoritariavotos = 1;
            }
        } else {
            $this->Session->write('flagminoritaria', 0);
        }

        return $minoritariavotos;
    }

    /**
     * suprimeTR method
     *
     * @param string $suprime
     * @return void
     */
    public function suprimeTR($suprime)
    {

        if ($this->request->data['Votacao']['tr_suprimida'] == 1) {

            /* Tem que verificar que selecionou resultado = 'suprimida' */
            if ($this->request->data['Votacao']['resultado'] !== 'suprimida') {
                // pr($this->request->data['Votacao']['resultado']);
                $this->Flash->error(__('Tem que selecionar "suprimida" também na caixa "Resolução".'));
                return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
            }

            /** Tem que verificar que o campo item_modificada está vazio */
            if (!empty($this->request->data['Votacao']['item_modificada'])) {
                $this->Flash->error(__('O campo Item modificado não está vazio. Verifique antes de suprimir a TR'));
                return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
            }

            /** Busco os items na tabela Item do evento_id */
            $evento_id = $this->Session->read('evento_id');
            $this->loadModel('Item');
            $this->Item->contain(['Apoio']);
            $items = $this->Item->find('all', [
                'conditions' => [
                    'and' => [
                        'Item.tr' => $this->request->data['Votacao']['tr'],
                        'Apoio.evento_id' => $evento_id
                    ]
                ]
            ]);
            foreach ($items as $c_item) {
                // echo substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item'] = substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item_id'] = $c_item['Item']['id'];

                $this->Votacao->create();
                if ($this->Votacao->save($this->request->data)) {
                    // pr($this->request->data);
                    // die();
                    // $this->Flash->success(__('Votação inserida como suprimida.'));
                } else {
                    // $this->Flash->error(__('Votação não foi inserida como suprimida. Tente novamente.'));
                }

            }
            return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
        }
    }

    public function usuario($data)
    {

        $this->request->data['Votacao'] = $data;

        if ($this->Auth->user('role') === 'admin') {

            $grupoId = $this->request->data['Votacao']['Votacao']['grupo'];
            $grupo = "grupo" . $grupoId;

            $this->loadModel('User');
            $usuarioData = $this->User->find('first', ['conditions' => ['User.username' => $grupo]]);
            $this->request->data['Votacao']['user_id'] = $usuarioData['User']['id'];
            // pr($this->request->data['Votacao']['user_id']);
            // die();

        }
        return $this->request->data['Votacao']['user_id'];
    }

    /* Na verdade todos podem ter acesso a esta função */

    public function view($id = null)
    {
        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Id inválidO'));
        }

        /* Verifica se o usuário pode executar esta ação */
        $votacao_grupo = $this->Votacao->findById($id, ['fields' => 'Votacao.grupo']);
        // pr($votacao_grupo['Votacao']['grupo']);
        // die();
        if ($this->Auth->user('role') == 'relator') {
            if (substr($this->Auth->user('username'), 5, 2) == $votacao_grupo['Votacao']['grupo']) {
                // echo "Usuario relator do mesmo grupo da votação autorizado";
                $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
                $this->set('votacao', $this->Votacao->find('first', $options));
            } else {
                // echo "Usuário relator de votação de outros grupos: não autorizado";
                $this->Flash->error(__('Ação não autorizada.'));
                return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao_grupo['Votacao']['grupo']]]);
            }
        } elseif ($this->Auth->user('role') == 'admin') {
            // echo "Admin autorizado";
            $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
            $this->set('votacao', $this->Votacao->find('first', $options));
        } else {
            $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
            $this->set('votacao', $this->Votacao->find('first', $options));
        }
        // die();
    }

    public function delete($id = null)
    {
        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Argumento inválido'));
        }

        $votacao = $this->Votacao->findById($id);

        if (!$votacao) {
            $this->Flash->error(__('Registro inexistente'));
            return $this->redirect(['action' => 'index']);
        }

        /* Relator e administrador podem excluir votações */
        if ($this->Auth->user('role') === 'relator' || $this->Auth->user('role') === 'admin') {
            if ($this->Votacao->delete($id)) {
                $this->Flash->success(__('Votação foi excluida.'));
                if ($this->Auth->user('role') === 'relator') {
                    /* Se eh relator vai para grupo */
                    return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);
                } elseif ($this->Auth->user('role') === 'admin') {
                    /* Se eh admin vai para tr */
                    return $this->redirect(['action' => 'index', '?' => ['tr' => $votacao['Votacao']['tr']]]);
                }
            } else {
                $this->Flash->error(__('Votação não foi excluida. Tente novamente.'));
                if ($this->Auth->user('role') === 'relator') {
                    /* Se eh relator vai para grupo */
                    return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);

                } elseif ($this->Auth->user('role') === 'admin') {
                    /* Se eh admin vai para tr */
                    return $this->redirect(['action' => 'index', '?' => ['tr' => $votacao['Votacao']['tr']]]);
                }
            }
            /* Editor nao pode excluir votacao */
        } elseif ($this->Auth->user('role') === 'editor') {
            $this->Flash->error(__('Ação não autorizada.'));
            return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);
        }
    }

    public function relatorio()
    {

        $evento_id = isset($this->request->query['evento_id']) ? $this->request->query['evento_id'] : $this->Session->read('evento_id');
        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', ['order' => 'ordem']);
        if (empty($evento_id)):
            $evento_id = end($eventos);
        endif;
        if ($evento_id) {
            $this->Session->write('evento_id', $evento_id);
        }
        // pr($eventos);
        // pr($evento_id);
        // die();
        $this->set('eventos', $eventos);
        $this->set('evento_id', $evento_id);

        $quantidade = NULL;
        $tr = NULL;
        /** Obtenho o grupo e o papel do usuário */
        $categoria = $this->autenticausuario();
        // pr($categoria);
        // die('relatorio');

        if ($this->request->data) {
            // pr($this->request->data);
            $dados = explode(',', $this->request->data['Relatorio']['trs']);
            // echo 'TR a serem processadas: ' . count($dados) . '<br>';
            // echo $condicoes;
            // TRs
            $i = 0;
            foreach ($dados as $c_dados) {
                // pr($c_dados);

                /* Relatorio por grupo ou total */
                if (!empty($categoria['grupo'])):
                    $relatorio[$i] = $this->Votacao->find(
                        'all',
                        [
                            'order' => ['Votacao.item, Votacao.grupo ASC'],
                            'conditions' => [
                                'Votacao.tr' => $c_dados,
                                'Votacao.grupo' => $categoria['grupo'],
                                'Votacao.evento_id' => $evento_id
                            ],
                        ]
                    );
                else:
                    $relatorio[$i] = $this->Votacao->find('all', [
                        'order' => ['Votacao.item, Votacao.grupo  ASC'],
                        'conditions' => [
                            'Votacao.tr' => $c_dados,
                            'Votacao.evento_id' => $evento_id
                        ],
                    ]);
                endif;

                if (count($relatorio[$i]) == 0):
                    if (isset($categoria['grupo'])):
                        echo $this->Flash->error(__('TR ' . $c_dados . ' sem votação neste grupo ' . $categoria['grupo'] . ' ou inexistente'));
                    else:
                        echo $this->Flash->error(__('TR ' . $c_dados . ' sem votação ou inexistente'));
                    endif;
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'relatorio', '?' => ['evento_id' => $evento_id]]);
                endif;

                $i++;
            }
            $tr_grupo = null;

            // Para cada TR
            for ($i = 0; $i < count($relatorio); $i++) {

                $aprovada = "<b>Items aprovados: </b> ";
                $modificada = "<b>Items modificados: </b> ";
                $suprimida = "<b>Items suprimidos: </b> ";
                $incluida = "<b>Novos items incluidos: </b> ";
                $minoritaria = "<b>Votação de item minoritário: </b>";
                $remitida = "<b>Remitidas: </b>";
                $outra = "<b>Outras votações: </b>";

                $suprimida_integralmente = NULL;
                $tr_suprimida = NULL;
                $taprovada = NULL;
                $tmodificada = NULL;
                $tsuprimida = NULL;
                $tincluida = NULL;
                $tminoritaria = NULL;
                $tremitida = NULL;
                $toutra = NULL;

                $qtr_suprimida = 0;
                $qaprovada = 0;
                $qmodificada = 0;
                $qsuprimida = 0;
                $qincluida = 0;
                $qminoritaria = 0;
                $qremitida = 0;
                $qoutra = 0;
                $grupos = NULL;
                $grupos_total = NULL;

                $tr_suprimida_grupo[] = NULL;

                // Captura as votações para cada item da TR
                for ($t = 0; $t < count($relatorio[$i]); $t++):
                    /* Para cada item localiza as items aprovadas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'aprovada'):
                        // echo $i . " Aprovada: " . "<br>";
                        $taprovada .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $taprovada .= " ";
                        $taprovada .= 'item: ' . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $taprovada .= " ";
                        $taprovada .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $taprovada .= ", ";
                        $qaprovada++;
                    endif;
                    /* Localiza as items modificadas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'modificada'):
                        // echo $i . "Modificada: ";
                        $tmodificada .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $tmodificada .= " ";
                        $tmodificada .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $tmodificada .= " ";
                        $tmodificada .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $tmodificada .= ", ";
                        $qmodificada++;
                    endif;

                    /** Localiza as TRs suprimidas integralmente */
                    if ($relatorio[$i][$t]['Votacao']['tr_suprimida'] == '1'):
                        if ($tr_grupo != $relatorio[$i][$t]['Votacao']['grupo']):
                            $tr_grupo = $relatorio[$i][$t]['Votacao']['grupo'];
                            $tr_suprimida .= $relatorio[$i][$t]['Votacao']['grupo'];
                            $tr_suprimida .= " ";
                            $tr_suprimida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ");";
                            $qtr_suprimida++;
                        endif;
                    endif;

                    /* Localiza as items suprimidas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'suprimida'):
                        // echo $i . "Suprimida: ";
                        $tsuprimida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $tsuprimida .= " ";
                        $tsuprimida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $tsuprimida .= " ";
                        $tsuprimida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $tsuprimida .= ", ";
                        $qsuprimida++;
                    endif;

                    // pr($relatorio[$i][$t]['Votacao']['resultado']);
                    /* Localiza as items incluídas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'inclusão'):
                        // echo $i . "Inclusão: ";
                        $tincluida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $tincluida .= " ";
                        // $tincluida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        // $tincluida .= " ";
                        $tincluida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $tincluida .= ", ";
                        $qincluida++;
                    endif;
                    /* Localiza as items minoritárias */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'minoritária'):
                        // echo $i . "Minoritárias: ";
                        $tminoritaria .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $tminoritaria .= " ";
                        $tminoritaria .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $tminoritaria .= " ";
                        $tminoritaria .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $tminoritaria .= ", ";
                        $qminoritaria++;
                    endif;
                    /* Localiza as items remitidas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'remitida'):
                        // echo $i . "Inclusão: ";
                        $tremitida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $tremitida .= " ";
                        $tremitida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $tremitida .= " ";
                        $tremitida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $tremitida .= ", ";
                        $qremitida++;
                    endif;
                    /* Localiza as items otras */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'outra'):
                        $toutra .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $toutra .= " ";
                        $toutra .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $toutra .= " ";
                        $toutra .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $toutra .= ", ";
                        $qoutra++;
                    endif;

                    $tr = $relatorio[$i][$t]['Votacao']['tr'];
                    /* Quais grupos trabalharam */
                    $grupos[] = $relatorio[$i][$t]['Votacao']['grupo'];

                    /**
                     * TRs sumprimidas integralmente
                     */
                    $suprimidas_integralmente = explode(';', $tr_suprimida);
                    $suprimidas_integralmente_sem_elementos_vazios = array_filter($suprimidas_integralmente, 'strlen');
                    $trs_suprimidas_integralmente = array_unique($suprimidas_integralmente_sem_elementos_vazios);
                    $trs_suprimidas_integralmente_string = implode(', ', $trs_suprimidas_integralmente);
                    // pr($trs_suprimidas_integralmente);

                endfor;

                // Junto tudo para fazer o texto
                if (empty($trs_suprimidas_integralmente)):
                    $suprimida_integralmente .= 0 . ", ";
                else:
                    $suprimida_integralmente .= $trs_suprimidas_integralmente_string;
                endif;

                if (empty($taprovada)):
                    $aprovada .= 0 . ", ";
                else:
                    $aprovada .= $taprovada;
                endif;

                if (empty($tmodificada)):
                    $modificada .= 0 . ", ";
                else:
                    $modificada .= $tmodificada;
                endif;

                if (empty($tsuprimida)):
                    $suprimida .= 0 . ", ";
                else:
                    $suprimida .= $tsuprimida;
                endif;

                if (empty($tincluida)):
                    $incluida .= 0 . ", ";
                else:
                    $incluida .= $tincluida;
                endif;

                if (empty($tminoritaria)):
                    $minoritaria .= 0 . ", ";
                else:
                    $minoritaria .= $tminoritaria;
                endif;

                if (empty($tremitida)):
                    $remitida .= 0 . ", ";
                else:
                    $remitida .= $tremitida;
                endif;

                if (empty($toutra)):
                    $outra .= 0 . ".";
                else:
                    $outra .= $toutra;
                endif;

                /* Grupos que analisaram o item */
                if (!empty($grupos)):
                    // pr($grupos);
                    $grupos_unicos = array_unique($grupos);
                    asort($grupos_unicos);
                    $grupos_total = implode(', ', $grupos_unicos);
                endif;
                // die();
                $quantidade[] = "<b>Grupo(s):</b> " . $grupos_total . '<br>' . "<b>TR: " . $tr . "</b>. " . "<b>Suprimida integralmente:</b> " . count($trs_suprimidas_integralmente) . ', <b>Aprovados:</b> ' . $qaprovada . ', <b>modificados:</b> ' . $qmodificada . ', <b>suprimidos:</b> ' . $qsuprimida . ', <b>incluídos:</b> ' . $qincluida . ', <b>minoritários:</b> ' . $qminoritaria . ', <b>remitidas:</b> ' . $qremitida . ' e <b>outras votações:</b> ' . $qoutra;

                $situacao_nos_grupos[] = "<b>TR: " . $tr . "</b>. " . "<b>" . "Suprimida integralmente no(s) grupo(s): " . "</b>" . $suprimida_integralmente . '.' . '<br />' . $aprovada . $modificada . $suprimida . $incluida . $minoritaria . $remitida . $outra . "<br>";

            }

            $this->set('relatorio', $relatorio);
            $this->set('situacao', $situacao_nos_grupos);
            $this->set('quantidade', $quantidade);
        }
    }

    public function evento($id = NULL)
    {

        $this->loadModel('Evento');
        $eventos = $this->Evento->find('list', [
            'order' => ['ordem' => 'asc']
        ]);
        end($eventos);
        return key($eventos);
    }

    /** Resolve a colation dos campos da tabela. Precisa crir campos auxiliares que serão excluídos depois */
    public function collation()
    {
        $this->autoRender = false;
        $votacoes = $this->Votacao->find('all');

        foreach ($votacoes as $votacao) {

            // pr($item['Item']['texto']);
            $votacaonova['Votacao']['id'] = $votacao['Votacao']['id'];
            $votacaonova['Votacao']['user_id'] = $votacao['Votacao']['user_id'];
            $votacaonova['Votacao']['evento_id'] = $votacao['Votacao']['evento_id'];
            $votacaonova['Votacao']['grupo'] = $votacao['Votacao']['grupo'];
            $votacaonova['Votacao']['tr'] = $votacao['Votacao']['tr'];
            $votacaonova['Votacao']['tr_suprimida'] = $votacao['Votacao']['tr_suprimida'];
            $votacaonova['Votacao']['tr_aprovada'] = $votacao['Votacao']['tr_aprovada'];
            $votacaonova['Votacao']['item_id'] = $votacao['Votacao']['item_id'];
            $votacaonova['Votacao']['item'] = $votacao['Votacao']['item'];
            $votacaonova['Votacao']['resultado'] = $votacao['Votacao']['resultado'];
            $votacaonova['Votacao']['resultado1'] = $votacao['Votacao']['resultado'];
            $votacaonova['Votacao']['votacao'] = $votacao['Votacao']['votacao'];
            $votacaonova['Votacao']['item_modificada'] = str_replace(["\r", "\n"], '', $votacao['Votacao']['item_modificada']);
            $votacaonova['Votacao']['item_modificada1'] = str_replace(["\r", "\n"], '', $votacao['Votacao']['item_modificada']);
            $votacaonova['Votacao']['data'] = $votacao['Votacao']['data'];
            $votacaonova['Votacao']['observacoes'] = $votacao['Votacao']['observacoes'];
            // $votacaonova['Votacao']['observacoes1'] = $votacao['Votacao']['observacoes'];
            // pr($apoionovo['Apoio']['texto1']);

            if ($this->Votacao->save($votacaonova)) {
                $this->Flash->success(__('Votação atualizada.'));
            } else {
                $this->Flash->error(__('Não foi possível atualizar a votação ' . $votacao['Votacao']['id'] . '. Tente novamente.'));
            }
        }
    }

    /** Insere o item_modificado da votação de 'inclusão' na tabela Item */
    public function insercao()
    {
        $this->Votacao->contain(['Item']);
        $insercao = $this->Votacao->find('all', [
            'conditions' => ['resultado' => 'inclusão']
        ]);
        // pr($insercao);

        $this->loadModel('Item');
        $items = $this->Item->find('all');
        foreach ($items as $item) {
            // pr(substr($item['Item']['item'], 2, 3));
            if (substr($item['Item']['item'], 2, 3) == '.99') {
                $this->Item->delete($item['Item']['id']);
            }
        }

        foreach ($insercao as $inserir) {
            // pr($inserir['Votacao']['tr']);
            $this->loadModel('Apoio');
            $apoio = $this->Apoio->find('first', [
                'and' => [
                    'Apoio.evento_id' => $inserir['Votacao']['evento_id'],
                    'Apoio.numero_texto' => $inserir['Votacao']['tr']
                ]
            ]);
            // pr($apoio);
            $inserirItem['Item']['apoio_id'] = $apoio['Apoio']['id'];
            $inserirItem['Item']['tr'] = $inserir['Votacao']['tr'];
            $inserirItem['Item']['item'] = $inserir['Votacao']['item'];
            $inserirItem['Item']['texto'] = str_replace(["\r", "\n"], '', $inserir['Votacao']['item_modificada']);
            // pr($inserirItem);

            $this->loadModel('Item');
            $this->Item->create();
            if ($this->Item->save($inserirItem)) {
                $this->Flash->success(__('Item insserido'));
                $inserir['Votacao']['item_id'] = $this->Item->getLastInsertID();
                if ($this->Votacao->save($inserir['Votacao'])) {
                    $this->Flash->success(__("Votação atualizada"));
                } else {
                    $this->Flash->error(__('Votação não atualizada'));
                }
            } else {
                echo "error" . "<br>";
                $this->Flash->error(__('Tentar novamente'));
            }
        }
    }

}
