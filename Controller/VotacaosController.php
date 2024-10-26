<?php

App::uses('AppController', 'Controller');

/**
 * Items Controller
 */
class VotacaosController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $components = array('Paginator', 'Session');
    public $helpers = array('Html', 'Form', 'Text');

    /*
      public function isAuthorized($user) {

      if (isset($user['role']) && $user['role'] === 'relator') {
      return true;
      }

      // All registered users can add posts
      if ($this->action === 'add') {
      return true;
      }

      // The owner of a post can edit and delete it
      if (in_array($this->action, array('add', 'edit', 'delete'))) {
      $votacaoId = $this->request->params['pass'][0];
      // echo $votacaoId;
      // die();
      if ($this->Votacao->isOwnedBy($votacaoId, $user['id'])) {
      return true;
      }
      }
      // pr($user);
      return parent::isAuthorized($user);
      }
     */

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

        // echo "Evento: " . $evento_id . "<br>";
        // echo "Grupo: " . $grupo . "<br>";
        // echo "Item: " . $item . "<br>";
        /// echo "Tr: " .$tr . "<br>";
        // echo "Item_id: " . $item_id . "<br>";
        // die();

        $this->Votacao->contain();
        if ($grupo and $item_id and $tr and $evento_id) {
            // die("1");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.item_id' => $item_id,
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $item and $evento_id) {
            // die("2");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.item_id' => $item_id,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $tr and $evento_id) {
            // die("3");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($grupo and $evento_id) {
            // die("4");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.grupo' => $grupo,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($item_id and $evento_id) {
            // die("5");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.item_id' => $item_id,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($tr and $evento_id) {
            // die("6");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.tr' => $tr,
                'Votacao.evento_id' => $evento_id
            ]));
        } elseif ($item_id and $evento_id) {
            // die("7");
            $this->set('votacaos', $this->Paginator->paginate('Votacao', [
                'Votacao.item_id' => $item_id,
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

    public function edit($id = NULL)
    {

        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Votação inválida'));
        }

        // $this->set('usuario', $this->autenticausuario());

        if ($this->Auth->user('role') == 'editor'):
            $this->Flash->error(__('Editor não pode atualizar votações.'));
            return $this->redirect(['action' => 'view', $this->request->data['Votacao']['id']]);
        endif;

        /* Executa a ação */
        if ($this->request->is(array('post', 'put'))):
            // pr($this->request->data);
            // die('post');

            /* Ajusto o valor do item_id em função do valor do item */
            if ($this->request->data['Votacao']['id']):
                $item_id = $this->Votacao->find('first', array(
                    'conditions' => [
                        'Votacao.id ' => $this->request->data['Votacao']['id']
                    ]
                ));
                // pr($item_id);
                // die();

                /**/
                if (!empty($item_id['Votacao']['id'])):
                    $this->request->data['Votacao']['item_id'] = $item_id['Votacao']['item_id'];
                else:
                    $this->request->data['Votacao']['item_id'] = 0;
                endif;

            endif;
            // pr($this->request->data);
            // die();

            /** Verifica se os dois primeiros dígitos do item correspondem com a TR na votação */
            // pr($this->request->data['Votacao']['tr']);
            // pr(substr($this->request->data['Votacao']['item'], 0, 2));
            // die();
            if (substr($this->request->data['Votacao']['item'], 0, 2) != $this->request->data['Votacao']['tr']) {
                $this->Flash->error(__('Os dois primeiros dígitos do campo Item tem que ser iguais ao TR.'));
                return $this->redirect(['action' => 'edit', $this->request->data['Votacao']['id']]);
            }

            if ($this->Votacao->save($this->request->data)):
                $this->Flash->success(__('Votação atualizada.'));
                return $this->redirect(['action' => 'view', $this->request->data['Votacao']['id']]);
            else:
                // pr($this->Votacao->validationErrors);
                $this->Flash->error(__('Votação não foi atualizada. Tente novamente.'));
            endif;
        else:
            $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
            $this->request->data = $this->Votacao->find('first', $options);
        endif;
    }

    /** Cuidado com esta função que altera  o user_id da tabela Votacao */
    public function atualizausuario()
    {

        $grupos = $this->Votacao->find('all', [
            'order' => ['grupo']
        ]);

        foreach ($grupos as $c_grupos):
            // pr($c_grupos['Votacao']['grupo']);

            $this->loadModel('User');
            $busca = 'grupo' . $c_grupos['Votacao']['grupo'];
            // echo $busca;
            $usuario = $this->User->find('all', [
                'conditions' => [
                    'User.username' => $busca
                ]
            ]);
            // pr($usuario);
            // echo ' ' . $i . " => Votação do grupo: " . $c_grupos['Votacao']['grupo'] . '<br>';

            if (!empty($usuario['User']['id'])):

                echo "Votação sem usuario de grupo?";
                // die();
                echo ' Id ' . $c_grupos['Votacao']['id'];
                echo " ";
                echo ' grupo: ' . $c_grupos['Votacao']['grupo'];
                echo " ";
                echo $usuario['User']['username'];
                echo " usuário id ";
                echo $usuario['User']['id'];
                echo "<br>";

            endif;
            // $log = $this->Votacao->getDataSource()->getLog(false, false);
            // debug($log);

            $sql = 'update votacaos set user_id = ' . $usuario[0]['User']['id'] . ' where id = ' . $c_grupos['Votacao']['id'];
            // echo $sql;
            // die(); 
            //$this->Votacao->query($sql);

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
            pr($evento_id);
            pr($item_id);
            pr($votacao_id);
            pr($resultado);
            // die();
        }

        /* Se o Id vem como parámentro (item_id) então é minoritária, senão é a primeira votacao */
        if (is_null($id)) {
            $id = $this->request->query['item_id'];
            // die('item_id');
        } else {
            $this->Session->write('flagminoritaria', 0);
        }
        // echo 'id 1 ' . $id . '<br>';
        // die();
        /** Envio o item para o add da view*/
        if ($id) {
            $this->loadModel('Item');
            $this->Item->contain(['Apoio']);
            $options = ['conditions' => ['Item.' . $this->Item->primaryKey => $id]];
            $this->set('item', $this->Item->find('first', $options));
        } else {
            $this->Flash->error(__('Selecione o item a ser votado.'));
            return $this->redirect(['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $evento_id]]);
        }
        // echo 'id 2 ' . $id . '<br>';
        // die();

        $this->set('usuario', $this->Auth->user());

        // Se o parametro resultado está presente eh porque eh uma votacao minoritaria
        if (isset($this->request->query['resultado'])):
            $resultado = $this->request->query['resultado'];
        endif;

        if (isset($resultado) && $resultado == 'minoritária'):
            // Se eh uma votacao minoritaria obtenho a votacao realizada para recuperar os resultados
            if (isset($this->request->query['votacao_id'])):
                $votacao_id = $this->request->query['votacao_id'];
            endif;

            if ($votacao_id):
                $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $votacao_id]];
                $this->Votacao->contain();
                $votacao = $this->Votacao->find('first', $options);
                // pr($votacao);
                $votacao['Votacao']['resultado'] = 'minoritária';
                // pr($votacao);
                $this->set('votacao', $votacao);
            else:
                $this->Flash->error(__('Sem votação anterior'));
                echo "Error: Sem votação anterior" . "<br>";
                exit;
                // die();
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
            // pr($this->request->data);
            // die('post');

            /* Calculo se eh minoritaria */
            if (isset($this->request->data['Votacao']['votacao'])):
                // die();
                // $this->Session->delete('flagminoritaria');
                $flag = $this->Session->read('flagminoritaria');
                echo "Entrada = " . $flag . "<br>";
                $minoritaria = $this->minoritaria($this->request->data['Votacao']['votacao']);
                if ($minoritaria == 1):
                    // $this->Flash->success(__('Votação minoritária.'));
                endif;
                $flag = $this->Session->read('flagminoritaria');
                echo "Saída = " . $flag . "<br>";
                // die();
            else:
                $this->Flash->error(__('Registre o resultado da votacao. Tente novamente.'));
                return $this->redirect(['controller' => 'Votacaos', 'action' => 'add', $id]);
            endif;

            // echo $this->Session->read('flagminoritaria') . '<br>';
            // echo $this->request->data['Votacao']['resultado'] . '<br>';
            // die();
            if (($this->request->data['Votacao']['resultado'] == 'inclusão') || ($this->request->data['Votacao']['resultado'] == 'minoritária')):
                // echo "modifica ou outros resultados" . '<br>';
            else:
                /* Busca se já foi votado o item pelo grupo e avisa no Flash. Não há impedimento (está certo?) */
                /* Function */
                $javotado = $this->Votacao->find('first', [
                    'conditions' => [
                        'Votacao.item_id' => $this->request->data['Votacao']['item_id'],
                        'Votacao.grupo' => $this->request->data['Votacao']['grupo'],
                        'Votacao.evento_id' => $evento_id,
                        'Votacao.resultado IN' => ['aprovada', 'modificada', 'suprimida', 'remitida']
                    ]
                ]);
                // pr($javotado);
                // die('javotado');

                if ($javotado):
                    $this->Flash->error(__("Item já foi votado pelo grupo."));
                    // return $this->redirect(['controller' => 'votacaos', 'action' => 'view', $javotado['Votacao']['id']]);
                endif;
            endif;

            /** Verifica se os dois primeiros dígitos do item correspondem com a TR na votação */
            // pr($this->request->data['Votacao']['tr']);
            // pr(substr($this->request->data['Votacao']['item'], 0, 2));
            // die();
            if (substr($this->request->data['Votacao']['item'], 0, 2) != $this->request->data['Votacao']['tr']) {
                $this->Flash->error(__('Os dois primeiros dígitos do campo Item tem que ser iguais ao TR.'));
                return $this->redirect(['action' => 'add', $id]);
            }

            /* Function suprime TR na sua totalidade */
            /* Quando selecionado 1 => Sim: cria um registo de supresão para cada item da TR. */
            if ($this->request->data['Votacao']['tr_suprimida'] == 1):
                // pr($this->request->data['Votacao']['tr_suprimida']);
                // die();
                $this->suprimeTR($this->request->data['Votacao']['tr_suprimida']);
            endif;

            /* Function aprovaembloco */
            /* Quando selecionado 1 => Sim: cria um registo de aprovação para cada item da TR excluindo os items que já foram votados. */
            if ($this->request->data['Votacao']['tr_aprovada'] == 1):
                // echo $this->request->data['Votacao']['tr_aprovada'];
                // die();
                $this->aprovaembloco($this->request->data);
            endif;

            if ($this->request->data['Votacao']['resultado'] == 'modificada'):
                if (empty($this->request->data['Votacao']['item_modificada'])):
                    $this->Flash->error(__("Registre a alteração do item da TR."));
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'add', $id]);
                endif;
            endif;

            /* Se é uma inclussão de um novo item tenho que criar o item na tabela */
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
                // pr($item_id);
                // die('item_id');

                $item['Item']['apoio_id'] = $item_id['Item']['apoio_id'];
                $item['Item']['tr'] = $this->request->data['Votacao']['tr'];
                $item['Item']['item'] = $this->request->data['Votacao']['item'];
                $item['Item']['texto'] = $this->request->data['Votacao']['item_modificada'];
                // pr($item);
                // die();

                $this->Item->create();
                if ($this->Item->save($item)):
                    echo "Item novo inserido";
                endif;

                /* Altero o valor do item_id com o id do item inserido */
                $this->request->data['Votacao']['item_id'] = $this->Item->id;
            }
            ;

            // die();
            // pr($this->minoritaria($this->request->data['Votacao']['votacao']));
            // pr($this->Session->read('flagminoritaria'));
            if ($this->Session->read('flagminoritaria') == 1):
                // die('flagminoritaria = 1');
            else:
                // die('flagminoritaria = 0');
            endif;
            // die();
            /* Finalmente insiro a votação do item */
            $this->Votacao->create();
            // pr($this->request->data);
            // pr($this->Session->read('flagminoritaria'));            
            // die();
            // pr($this->Votacao->validationErrors);
            if ($this->Votacao->save($this->request->data)):
                // pr($this->minoritaria($this->request->data['Votacao']['votacao']));
                // die();
                $flagminoritaria = $this->Session->read('flagminoritaria');
                // echo 'Flag ' . $flagminoritaria;
                // die('flag');
                if ($flagminoritaria == '1'):
                    // die('votacao minoritaria');
                    $this->Flash->success(__('Votação inserida. Registre a votação minoritária'));
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'add', '?' => ['item_id' => $this->request->data['Votacao']['item_id'], 'votacao_id' => $this->Votacao->getLastInsertID(), 'resultado' => 'minoritária']]);
                    // die('votacao minoritaria ');
                else:
                    $this->Flash->success(__('Votação inserida.'));
                    // die('votacao normal');
                    return $this->redirect(['controller' => 'Votacaos', 'action' => 'view', $this->Votacao->getLastInsertID()]);
                endif;
            else:
                $errors = $this->Votacao->validationErrors;
                pr($errors);
                // die();
                $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
            endif;
        }
    }

    public function itemId($dados)
    {

        if ($dados) {
            /* Votação de inclusao de item novo: inclusao. O campo item_id fica em 0 */
            $items = explode(".", $dados);

            /* Atribui 0 ao item_id e TR.99 ao item */
            if ($this->request->data['Votacao']['resultado'] == 'inclusão'):
                $this->request->data['Votacao']['item_id'] = 0;
                $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['tr'] . '.99';
            else:
                /* Capturo o valor do id da tabela Item para inserir no campo item_id da tabela Votacao */
                $this->loadModel('Item');
                $this->Item->contain();
                $outro_item = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.item = ' . $this->request->data['Votacao']['item']
                    )
                ));
                // echo $this->request->data['Votacao']['item'];
                // pr($outro_item['Item']['id']);
                // die();
                if (!empty($outro_item['Item']['id'])):
                    // Votação de aprovação, modificação, supresão, minoritária, remissão, outro. O campo item_id eh capturado da tabela Item
                    $this->request->data['Votacao']['item_id'] = $outro_item['Item']['id'];
                    // pr($this->request->data['Votacao']['item_id']);
                    // die('item_id');
                else:
                    $this->Flash->error(__('O item não existe. Inserir novo item na TR'));
                    return $this->redirect(array('controller' => 'Items', 'action' => 'add'));
                endif;
            endif;
        }
        return $this->request->data['Votacao']['item_id'];
    }

    /** Aprova todos os items da TR em bloco */
    public function aprovaembloco($dados)
    {

        $evento_id = $this->Session->read('evento_id');
        if ($this->request->data['Votacao']['tr_aprovada'] == 1):
            // echo $this->request->data['Votacao']['tr_aprovada'];

            /* Busco os items na tabela item do evento_id */
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

            foreach ($items as $c_item):
                // echo substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item'] = substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item_id'] = $c_item['Item']['id'];
                // pr($this->request->data);
                /* Verifico se já foi votado */
                $javotado = $this->Votacao->find('first', [
                    'conditions' => [
                        'and' => [
                            'Votacao.item' => $this->request->data['Votacao']['item'],
                            'Votacao.grupo' => $this->request->data['Votacao']['grupo'],
                            'Votacao.evento_id' => $evento_id
                        ]
                    ]
                ]);

                // die();
                /* Se não foi votado o item então insiro os valores de aprovação */
                if (sizeof($javotado) == 0):
                    $this->Votacao->create();
                    // $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['numero_item'];
                    if ($this->Votacao->save($this->request->data)):
                        // pr($this->request->data);
                        $this->Flash->success(__('Votação inserida.'));
                    else:
                        $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
                    endif;
                    // die('Item aprovado não foi inserido?');
                endif;

            endforeach;
            return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
            // die();
        endif;
    }

    public function minoritaria($dados)
    {

        $verifica = strpos($dados, '/');
        // pr($verifica);
        // die();
        if ($verifica === false):
            $votos = explode('-', $dados);
        else:
            $votos = explode('/', $dados);
        endif;
        // pr($votos);
        // die();
        $totalvotos = $votos[0] + $votos[1] + $votos[2];
        $tercovotos = $totalvotos / 3;
        // echo $totalvotos . ' Terço => ' . $tercovotos . ' Votos do segundo resultado => ' . $votos[1];
        // die();
        $minoritariavotos = NULL;
        if ($votos[1] >= $tercovotos):
            // echo "Minoritária" . "<br>";
            // die();
            /* Se o cookie existe então apago para saber que não tem que salvar novamente */
            // echo $this->Session->read('flagminoritaria') . "<br>";
            // echo 'Flag: ' . $flagmarioritaria;
            if ($this->Session->read('flagminoritaria') == 1):
                $this->Session->write('flagminoritaria', 0);
                $minoritariavotos = 0;
            elseif ($this->Session->read('flagminoritaria') == 0):
                $this->Session->write('flagminoritaria', 1);
                $minoritariavotos = 1;
            endif;
        else:
            $this->Session->write('flagminoritaria', 0);
        endif;
        // pr($minoritariavotos);
        // die('function minoritaria');
        return $minoritariavotos;
    }

    public function suprimeTR($suprime)
    {

        if ($this->request->data['Votacao']['tr_suprimida'] == 1):
            // pr($this->request->data['Votacao']['tr_suprimida']);
            // die("function suprimeTR");

            /* Tem que verificar que selecionou resultado = 'suprimida' */
            if ($this->request->data['Votacao']['resultado'] !== 'suprimida'):
                // pr($this->request->data['Votacao']['resultado']);
                $this->Flash->error(__('Tem que selecionar "suprimida" também na caixa "Resolução".'));
                return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]));
            endif;

            // pr($this->request->data['Votacao']['item_modificada']);
            // die();
            /** Tem que verificar que o campo item_modificada está vazio */
            if (!empty($this->request->data['Votacao']['item_modificada'])):
                // pr($this->request->data['Votacao']['item_modificada']);
                // die();
                $this->Flash->error(__('O campo Item modificado não está vazio. Verifique antes de suprimir a TR'));
                return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]));
            endif;

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
            // $log = $this->Votacao->getDataSource()->getLog(false, false);
            // debug($log);
            // pr($items);
            // die();
            foreach ($items as $c_item):
                // echo substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item'] = substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item_id'] = $c_item['Item']['id'];
                // pr($this->request->data);
                // die();
                $this->Votacao->create();
                // $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['numero_item'];
                if ($this->Votacao->save($this->request->data)):
                    // pr($this->request->data);
                    // die();
                    $this->Flash->success(__('Votação inserida.'));
                else:
                    $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
                endif;

            endforeach;
            // die();
            return $this->redirect(['controller' => 'Votacaos', 'action' => 'index', '?' => ['tr' => substr($this->request->data['Votacao']['item'], 0, 2)]]);
            // die();
        endif;
    }

    public function usuario($data)
    {

        $this->request->data['Votacao'] = $data;

        if ($this->Auth->user('role') === 'admin'):

            $grupoId = $this->request->data['Votacao']['Votacao']['grupo'];
            $grupo = "grupo" . $grupoId;

            $this->loadModel('User');
            $usuarioData = $this->User->find('first', array('conditions' => array('User.username' => $grupo)));
            $this->request->data['Votacao']['user_id'] = $usuarioData['User']['id'];
            // pr($this->request->data['Votacao']['user_id']);
            // die();

        endif;
        return $this->request->data['Votacao']['user_id'];
    }

    /* Na verdade todos podem ter acesso a esta função */

    public function view($id = null)
    {
        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Id inválidO'));
        }
        // $this->set('usuario', $this->autenticausuario());

        /* Verifica se o usuário pode executar esta ação */
        $votacao_grupo = $this->Votacao->findById($id, ['fields' => 'Votacao.grupo']);
        // pr($votacao_grupo['Votacao']['grupo']);
        // die();
        if ($this->Auth->user('role') == 'relator'):
            if (substr($this->Auth->user('username'), 5, 2) == $votacao_grupo['Votacao']['grupo']):
                // echo "Usuario relator do mesmo grupo da votação autorizado";
                $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
                $this->set('votacao', $this->Votacao->find('first', $options));
            else:
                // echo "Usuário relator de votação de outros grupos: não autorizado";
                $this->Flash->error(__('Ação não autorizada.'));
                return $this->redirect(array('action' => 'index', '?' => ['grupo' => $votacao_grupo['Votacao']['grupo']]));
            endif;
        elseif ($this->Auth->user('role') == 'admin'):
            // echo "Admin autorizado";
            $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
            $this->set('votacao', $this->Votacao->find('first', $options));
        else:
            $options = ['conditions' => ['Votacao.' . $this->Votacao->primaryKey => $id]];
            $this->set('votacao', $this->Votacao->find('first', $options));
            // $this->Flash->error(__('Ação não autorizada.'));
            // return $this->redirect(array('action' => 'index/grupo:' . $votacao_grupo['Votacao']['grupo']));
        endif;
        // die();
    }

    public function delete($id = null)
    {

        $this->Votacao->id = $id;

        $votacao = $this->Votacao->findById($id);
        // pr($votacao["Votacao"]['tr']);
        // die();

        if (!$this->Votacao->exists()) {
            throw new NotFoundException(__('Invalid número'));
        }

        /* Relator e administrador podem excluir votações */
        if ($this->Auth->user('role') === 'relator' || $this->Auth->user('role') === 'admin'):
            if ($this->Votacao->delete()):
                $this->Flash->success(__('Votação foi excluida.'));
                if ($this->Auth->user('role') === 'relator'):
                    /* Se eh relator vai para grupo */
                    return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);

                elseif ($this->Auth->user('role') === 'admin'):
                    /* Se eh admin vai para tr */
                    return $this->redirect(['action' => 'index', '?' => ['tr' => $votacao['Votacao']['tr']]]);
                endif;
            else:
                $this->Flash->error(__('Votação não foi excluida. Tente novamente.'));
                if ($this->Auth->user('role') === 'relator'):
                    /* Se eh relator vai para grupo */
                    return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);

                elseif ($this->Auth->user('role') === 'admin'):
                    /* Se eh admin vai para tr */
                    return $this->redirect(['action' => 'index', '?' => ['tr' => $votacao['Votacao']['tr']]]);
                endif;
            endif;
            /* Editor nao podo excluir votacao */
        elseif ($this->Auth->user('role') === 'editor'):
            $this->Flash->error(__('Ação não autorizada.'));
            return $this->redirect(['action' => 'index', '?' => ['grupo' => $votacao['Votacao']['grupo']]]);
        endif;
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
                            'order' => ['Votacao.item, Votacao.grupo  ASC'],
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
                    return $this->redirect(array('controller' => 'Votacaos', 'action' => 'relatorio', '?' => ['evento_id' => $evento_id]));
                endif;

                $i++;
            }

            // Para cada TR
            for ($i = 0; $i < count($relatorio); $i++) {

                $aprovada = "<b>Aprovados: </b> ";
                $modificada = "<b>Modificados: </b> ";
                $suprimida = "<b>Suprimidos: </b> ";
                $incluida = "<b>Inclusões: </b> ";
                $minoritaria = "<b>Minoritárias: </b>";
                $remitida = "<b>Remitidas: </b>";
                $outra = "<b>Outras votações: </b>";

                $taprovada = NULL;
                $tmodificada = NULL;
                $tsuprimida = NULL;
                $tincluida = NULL;
                $tminoritaria = NULL;
                $tremitida = NULL;
                $toutra = NULL;

                $qaprovada = 0;
                $qmodificada = 0;
                $qsuprimida = 0;
                $qincluida = 0;
                $qminoritaria = 0;
                $qremitida = 0;
                $qoutra = 0;
                $grupos = NULL;
                $grupos_total = NULL;

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

                endfor;

                // Junto tudo para fazer o texto
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
                $quantidade[] = "<b>Grupo(s):</b> " . $grupos_total . '<br>' . "<b>TR: " . $tr . "</b>. " . '<b>Aprovados:</b> ' . $qaprovada . ', <b>modificados:</b> ' . $qmodificada . ', <b>suprimidos:</b> ' . $qsuprimida . ', <b>incluídos:</b> ' . $qincluida . ', <b>minoritários:</b> ' . $qminoritaria . ', <b>remitidas:</b> ' . $qremitida . ' e <b>outras votações:</b> ' . $qoutra;

                $situacao_nos_grupos[] = "<b>TR: " . $tr . "</b>. " . $aprovada . $modificada . $suprimida . $incluida . $minoritaria . $remitida . $outra . "<br>";
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
}
