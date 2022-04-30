<?php

App::uses('AppController', 'Controller');

/**
 * Items Controller
 */
class VotacaosController extends AppController {

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $components = array('Paginator', 'Session');
    public $helpers = array('Html', 'Form', 'Text');

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

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('add', 'edit', 'delete', 'relatorio');
// pr($this->isAuthorized($user));

        $usuario = $this->autenticausuario();
// pr($this->Auth);
// debug($this->Auth->allow);
    }

    public function index($id = NULL) {

        $usuario = $this->autenticausuario();
        $item = NULL;
        $tr = NULL;
        $grupo = NULL;

        if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
            if (isset($this->params['named']['grupo'])):
                $grupo = $this->params['named']['grupo'];
            endif;
        elseif ($usuario['papel'] == 'relator'):
            $grupo = $usuario['grupo'];
        endif;

        if (isset($this->params['named']['item'])) {
            $item = $this->params['named']['item'];
        }

        if (isset($this->params['named']['tr'])) {
            $tr = $this->params['named']['tr'];
        }

// pr($id);
        $this->Votacao->recursive = 2;
        if ($grupo and $item and $tr) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.grupo' => $grupo, 'Votacao.item' => $item, 'Votacao.tr' => $tr)));
        } elseif ($grupo and $item) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.grupo' => $grupo, 'Votacao.item' => $item)));
        } elseif ($grupo and $tr) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.grupo' => $grupo, 'Votacao.tr' => $tr)));
        } elseif ($grupo) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.grupo' => $grupo)));
        } elseif ($item) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.item' => $item)));
        } elseif ($tr) {
            $this->set('votacaos', $this->Paginator->paginate('Votacao', array(
                        'Votacao.tr' => $tr)));
        } else {
            $this->set('votacaos', $this->Paginator->paginate());
        }
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);

        $this->set('grupos', $grupos = $this->Votacao->find('all', array(
            'fields' => array('DISTINCT Votacao.grupo as grupo '),
            'order' => array('Votacao.grupo ASC')
        )));
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);
// pr($grupos);
    }

    public function edit($id = NULL) {

        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Votação inválida'));
        }

        if ($this->Auth->user('role') === 'editor'):
            $this->Flash->error(__('Editor não pode atualizar votações.'));
            return $this->redirect(array('action' => 'view/' . $this->request->data['Votacao']['id']));
        endif;

        /* Executa a ação */
        if ($this->request->is(array('post', 'put'))):
// pr($this->request->data);
// die();

            /* Ajusto o valor do item_id em função do valor do item */
            if ($this->request->data['Votacao']['item']):
                $this->loadModel('Item');
                $itemId = $this->Item->find('first', array(
                    'conditions' => array('Item.item = ' . $this->request->data['Votacao']['item']
                )));
// pr($outro_item);
// die();

                /**/
                if (!empty($itemId['Item']['id'])):
                    $this->request->data['Votacao']['item_id'] = $itemId['Item']['id'];
                else:
                    $this->request->data['Votacao']['item_id'] = 0;
                endif;

            endif;
// pr($this->request->data);
// die;
// die();
            if ($this->Votacao->save($this->request->data)):
                $this->Flash->success(__('Votação atualizada.'));
                return $this->redirect(array('action' => 'view/' . $this->request->data['Votacao']['id']));
            else:
                $this->Flash->error(__('Votação não foi atualizada. Tente novamente.'));
            endif;

        else:
            $options = array('conditions' => array('Votacao.' . $this->Votacao->primaryKey => $id));
            $this->request->data = $this->Votacao->find('first', $options);
            $this->request->data['Votacao']['numero_item'] = $this->request->data['Votacao']['item_id'];
        endif;
    }

    /* Cuidado com esta função que altera  o user_id da tabela Votacao */

    public function atualizausuario() {

        $grupos = $this->Votacao->find('all', array(''
            . 'order' => array('grupo')));

        foreach ($grupos as $c_grupos):
// pr($c_grupos['Votacao']['grupo']);

            $this->loadModel('User');
            $busca = 'grupo' . $c_grupos['Votacao']['grupo'];
// echo $busca;
            $usuario = $this->User->find('all', array(
                'conditions' => array(
                    'User.username' => $busca)));

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
            // die();            // 
//$this->Votacao->query($sql);

        endforeach;
    }

    public function add($id = NULL) {

        if ($id) {

            $this->loadModel('Item');
            $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
            $this->set('item', $this->Item->find('first', $options));
        }

        if (isset($this->params['named']['grupo'])):
            $grupo = $this->params['named']['grupo'];
            $this->set('grupo', $grupo);
        endif;

        if (isset($this->params['named']['tr'])):
            $tr = $this->params['named']['tr'];
            $this->set('tr', $tr);
        endif;

        if (isset($this->params['named']['item'])) {
            $item = $this->params['named']['item'];
            $options = array('conditions' => array('Item.item' => $item));

            $this->loadModel('Item');
            $this->set('item', $this->Item->find('first', $options));
        }

        /* Quando a votação e minoritaria eh passado o valor da votacao */
        if (isset($this->params['named']['votacao'])):
            $votacao = $this->params['named']['votacao'];
            $this->set('votacao', $votacao);
        endif;

        if ($this->request->is('post')) {
// pr($this->request->data);

            /* Exepcionalmente se a votação é do usuario admin */
// pr($this->Auth->user('role'));
            if ($this->Auth->user('role') === 'admin'):
                $usuarioData = $this->usuario($this->request->data);
// pr($usuarioData);
                $this->request->data['Votacao']['Votacao']['user_id'] = $usuarioData;
                $this->request->data = $this->request->data["Votacao"];
// die(pr($this->request->data));
            else:
                $this->request->data['Votacao']['user_id'] = $this->Auth->user('id');
            endif;

            /* Function itemId */
            /* A partir do item busco o id na tabela Item. */
            /* O problema aqui eh que será criada uma votação que NÃO corresponde a um item. Por isso a relação hasMany está quebrada */
            if ($this->request->data['Votacao']['item']):
                $this->itemId($data);
            endif;

            /* Calculo se eh minoritaria e avisa no Flash. */
            if (isset($this->request->data['Votacao']['votacao'])):
// die();
                $minoritaria = $this->minoritaria($this->request->data);
// echo $minoritaria;
                echo $this->Session->read('flagminoritaria');
// die();
            else:
                $this->Flash->error(__('Registre o resultado da votacao. Tente novamente.'));
                return $this->redirect(array('controller' => 'Votacaos', 'action' => 'add/item:' . $this->request->data['Votacao']['item']));
            endif;

            /* Busca se já foi votado o item pelo grupo e avisa no Flash. Não há impedimento (está certo?) */
            /* Function */
            $javotado = $this->Votacao->find('first', array(
                'conditions' => array(
                    'Votacao.item' => $this->request->data['Votacao']['item'],
                    'Votacao.grupo' => $this->request->data['Votacao']['grupo'])));
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);
// pr($javotado);
            $atencao = NULL;
            if (count($javotado) > 0):
                if (substr($javotado['Votacao']['item'], 3, 2) !== 99):
                    $atencao = ' Atenção!: Item já foi votado pelo grupo.';
                endif;
            endif;

            /* Function suprime TR */
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

            /* Finalmente insiro a votação do item */
            $this->Votacao->create();
// pr($this->request->data);
// $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['numero_item'];
            if ($this->Votacao->save($this->request->data)):
// die();
                if ($minoritaria):
                    $this->Flash->success(__('Votação inserida. Registre a votação minoritária'));
                    return $this->redirect(array('controller' => 'Votacaos', 'action' => 'add/' . 'tr:' . $this->request->data['Votacao']['tr'] . '/item:' . $this->request->data['Votacao']['item'] . '/grupo:' . $this->request->data['Votacao']['grupo'] . '/votacao:' . str_replace("/", "-", $this->request->data['Votacao']['votacao'])));
                else:
                    $this->Flash->success(__('Votação inserida.'));
                    return $this->redirect(array('controller' => 'Votacaos', 'action' => 'view/' . $this->Votacao->getLastInsertID()));
                endif;
            else:
                $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
            endif;
        }
    }

    public function itemId($dados) {

        if ($this->request->data['Votacao']['item']) {
// die();
            /* Votação de inclusao de item novo: inclusao. O campo item_id fica em 0 */
            $items = explode(".", $this->request->data['Votacao']['item']);

            /* Atribui 0 ao item_id e TR.99 ao item */
            if ($this->request->data['Votacao']['resultado'] == 'inclusão'):
                $this->request->data['Votacao']['item_id'] = 0;
                $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['tr'] . '.99';
// pr($items);
// die();
            else:
                /* Capturo o valor do id da tabela Item para inserir no campo item_id da tabela Votacao */
                $this->loadModel('Item');
                $outro_item = $this->Item->find('first', array(
                    'conditions' => array('Item.item = ' . $this->request->data['Votacao']['item']
                )));
// echo $this->request->data['Votacao']['item'];
// pr($outro_item);
// die();
                if (!empty($outro_item['Item']['id'])):
// Votação de aprovação, modificação, supresão, minoritária, remissão, outro. O campo item_id eh capturado da tabela Item
                    $this->request->data['Votacao']['item_id'] = $outro_item['Item']['id'];
                else :
                    $this->Flash->error(__('O item não existe. Inserir novo item na TR'));
                    return $this->redirect(array('controller' => 'Items', 'action' => 'add'));
                endif;
            endif;
        }
    }

    public function aprovaembloco($dados) {

        if ($this->request->data['Votacao']['tr_aprovada'] == 1):
// echo $this->request->data['Votacao']['tr_aprovada'];
// die();

            /* Busco os items na tabela item */
            $this->loadModel('Item');
            $items = $this->Item->find('all', array(
                'conditions' => array('Item.item LIKE' => substr($this->request->data['Votacao']['item'], 0, 2) . '%')));
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);
// pr($items);
// die();
// echo count($items);
            foreach ($items as $c_item):
// echo substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item'] = substr($c_item['Item']['item'], 0, 5);
                $this->request->data['Votacao']['item_id'] = $c_item['Item']['id'];

// pr($this->request->data);
// die();

                /* Verifico se já foi votado */
                $javotado = $this->Votacao->find('first', array(
                    'conditions' => array(
                        'Votacao.item' => $this->request->data['Votacao']['item'],
                        'Votacao.grupo' => $this->request->data['Votacao']['grupo'])));
// pr($javotado);
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);

                /* Se não foi votado o item então insiro os valores de aprovação */
                if (count($javotado) == 0):
                    $this->Votacao->create();
// $this->request->data['Votacao']['item'] = $this->request->data['Votacao']['numero_item'];
                    if ($this->Votacao->save($this->request->data)):
// pr($this->request->data);
// die();
                        $this->Flash->success(__('Votação inserida.'));
                    else:
                        $this->Flash->error(__('Votação não foi inserida. Tente novamente.'));
                    endif;
// die('Item aprovado não foi inserido?');
                endif;

            endforeach;
// die();
            return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index/tr:' . substr($this->request->data['Votacao']['item'], 0, 2)));
// die();
        endif;
    }

    public function minoritaria($dados) {

        $votos = explode('/', $this->request->data['Votacao']['votacao']);
// pr($votos);
// die();
        $totalvotos = $votos[0] + $votos[1] + $votos[2];
        $tercovotos = $totalvotos / 3;
// echo 'Terço => ' . $tercovotos . ' Votos do segundo resultado => ' . $votos[1];
        $minoritariavotos = NULL;
        if ($votos[1] >= $tercovotos):
            echo "Minoritária" . "<br>";
// die();
            $this->Session->check('flagminoritaria');
// die($this->Session->check('flagminoritaria'));
            if ($this->Session->check('flagminoritaria')):
                echo $this->Session->read('flagminoritaria');
// echo 'Flag: ' . $flagmarioritaria;
                if ($this->Session->read('flagminoritaria') === 1):
                    $minoritariavotos = NULL;
                    $this->Session->delete('flagminoritaria');
                endif;
// die('Delete flag');
            else:
                $this->Session->write('flagminoritaria', 1);
                $minoritariavotos = 'Minoritária';
// die('Passou!');
            endif;
        else:
            $this->Session->delete('flagminoritaria');
        endif;
        echo $minoritariavotos;
// $this->Session->destroy();
// die();
        return ($minoritariavotos);
    }

    public function suprimeTR($suprime) {

        if ($this->request->data['Votacao']['tr_suprimida'] == 1):
// pr($this->request->data['Votacao']['tr_suprimida']);
// die("function");

            /* Tem que verificar que selecionou resultado = 'suprimida' */
            if ($this->request->data['Votacao']['resultado'] !== 'suprimida'):
// pr($this->request->data['Votacao']['resultado']);
                $this->Flash->error(__('Tem que selecionar "suprimida" também na caixa "Resolução".'));
                return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index/tr:' . substr($this->request->data['Votacao']['item'], 0, 2)));
            endif;

// pr($this->request->data['Votacao']['item_modificada']);
// die();
            /* Tem que verificar que o campo item_modificada está vazio */
            if (!empty($this->request->data['Votacao']['item_modificada'])):
// pr($this->request->data['Votacao']['item_modificada']);
// die();
                $this->Flash->error(__('O campo Item modificado não está vazio. Verifique antes de suprimir a TR'));
                return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index/tr:' . substr($this->request->data['Votacao']['item'], 0, 2)));
            endif;

// die("Pasou!");

            /* Busco os items na tabela Item */
            $this->loadModel('Item');
            $items = $this->Item->find('all', array(
                'conditions' => array('Item.item LIKE' => substr($this->request->data['Votacao']['item'], 0, 2) . '%')));
// $log = $this->Votacao->getDataSource()->getLog(false, false);
// debug($log);
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
            return $this->redirect(array('controller' => 'Votacaos', 'action' => 'index/tr:' . substr($this->request->data['Votacao']['item'], 0, 2)));
// die();
        endif;
    }

    public function usuario($data) {

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

    public function view($id = null) {

        if (!$this->Votacao->exists($id)) {
            throw new NotFoundException(__('Id inválidO'));
        }

        /* Verifica se o usuário pode executar esta ação */
        $votacao_grupo = $this->Votacao->findById($id, array('fields' => 'Votacao.grupo'));
        // pr($votacao_grupo['Votacao']['grupo']);
// echo substr($this->Auth->user('username'), 5, 2);
// echo " ";
// echo $votacao_grupo['Votacao']['grupo'];
// die();
        if ($this->Auth->user('role') == 'relator'):
            if (substr($this->Auth->user('username'), 5, 2) == $votacao_grupo['Votacao']['grupo']):
                echo "Usuario relator do mesmo grupo da votação autorizado";
                $options = array('conditions' => array('Votacao.' . $this->Votacao->primaryKey => $id));
                $this->set('votacao', $this->Votacao->find('first', $options));
            else:
                echo "Usuário relator de votação de outros grupos: não autorizado";
                $this->Flash->error(__('Ação não autorizada.'));
                return $this->redirect(array('action' => 'index/grupo:' . $votacao_grupo['Votacao']['grupo']));
            endif;
        elseif ($this->Auth->user('role') == 'admin'):
            echo "Admin autorizado";
            $options = array('conditions' => array('Votacao.' . $this->Votacao->primaryKey => $id));
            $this->set('votacao', $this->Votacao->find('first', $options));
        else:
            $this->Flash->error(__('Ação não autorizada.'));
            return $this->redirect(array('action' => 'index/grupo:' . $votacao_grupo['Votacao']['grupo']));
        endif;
        // die();

    }

    public function delete($id = null) {

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
                    return $this->redirect(array('action' => 'index/grupo:' . $votacao['Votacao']['grupo']));

                elseif ($this->Auth->user('role') === 'admin'):
                    /* Se eh admin vai para tr */
                    return $this->redirect(array('action' => 'index/tr:' . $votacao['Votacao']['tr']));
                endif;
            else:
                $this->Flash->error(__('Votação não foi excluida. Tente novamente.'));
                if ($this->Auth->user('role') === 'relator'):
                    /* Se eh relator vai para grupo */
                    return $this->redirect(array('action' => 'index/grupo:' . $votacao['Votacao']['grupo']));

                elseif ($this->Auth->user('role') === 'admin'):
                    /* Se eh admin vai para tr */
                    return $this->redirect(array('action' => 'index/tr:' . $votacao['Votacao']['tr']));
                endif;
            endif;
        /* Editor nao podo excluir votacao */
        elseif ($this->Auth->user('role') === 'editor'):
            $this->Flash->error(__('Ação não autorizada.'));
            return $this->redirect(array('action' => 'index/grupo:' . $votacao['Votacao']['grupo']));
        endif;
    }

    public function relatorio() {

        $quantidade = NULL;
        $tr = NULL;
        $usuario = $this->autenticausuario();
// pr($usuario);

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
                if (!empty($usuario['grupo'])):
                    $relatorio[$i] = $this->Votacao->find('all', array(
                        'order' => array('Votacao.item, Votacao.grupo  ASC'),
                        'conditions' => array('Votacao.tr' => $c_dados,
                            'Votacao.grupo' => $usuario['grupo']),
                    ));
                else:
                    $relatorio[$i] = $this->Votacao->find('all', array(
                        'order' => array('Votacao.item, Votacao.grupo  ASC'),
                        'conditions' => array('Votacao.tr' => $c_dados),
                    ));
                endif;

                if (count($relatorio[$i]) == 0):
                    if (isset($usuario['grupo'])):
                        echo $this->Flash->error(__('TR ' . $c_dados . ' sem votação neste grupo ' . $usuario['grupo'] . ' ou inexistente'));
                    else:
                        echo $this->Flash->error(__('TR ' . $c_dados . ' sem votação ou inexistente'));
                    endif;
                    return $this->redirect(array('controller' => 'Votacaos', 'action' => 'relatorio'));
                endif;

                $i++;
            }
// echo $c_dados;
// echo 'TRs : ' . count($relatorio) . '<br> ';
            for ($i = 0; $i < count($relatorio); $i++) {
                $aprovada = "<b>Aprovados: </b> ";
                $modificada = "<b>Modificados: </b> ";
                $suprimida = "<b>Suprimidos: </b> ";
                $incluida = "<b>Inclusões: </b> ";
                $minoritaria = "<b>Minoritárias: </b>";
                $remitida = "<b>Remitidas: </b>";
                $outra = "<b>Outras votações: </b>";
//    echo 'I : ' . $i . '<br>';
// echo 'Items da TR: ' . count($relatorio[$i]) . '<br> ';
// pr($relatorio);
                $qaprovada = 0;
                $qmodificada = 0;
                $qsuprimida = 0;
                $qincluida = 0;
                $qminoritaria = 0;
                $qremitida = 0;
                $qoutra = 0;
                $grupos = NULL;
                $grupos_total = NULL;

                for ($t = 0; $t < count($relatorio[$i]); $t++):
// echo 'TR -> ' . $i . ' ' . $relatorio[$i][$t]['Votacao']['tr'] .' ';
// echo 'item: -> ' . $t . ' <br> ';
// echo $tr = $relatorio[$i][$t]['Votacao']['tr'] . '<br>';
// pr($relatorio[$i][$t]['Votacao']);
// echo substr($relatorio[$i][$t]['Votacao']['item'], 3, 2) . '<br>';
// $item0 = substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
// $item1 = substr($relatorio[$i][$t + 1]['Votacao']['item'], 3, 2);
// if ($item1 > $item0):
// echo "Novo item " . $i . " " .  $t .  "<br>";
// echo $item = $relatorio[0][50]['Item']['texto'] . "<br>";
// endif;
                    /* Para cada item localiza as items aprovadas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'aprovada'):
// echo $i . " Aprovada: " . "<br>";
                        $aprovada .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $aprovada .= " ";
                        $aprovada .= 'item: ' . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $aprovada .= " ";
                        $aprovada .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $aprovada .= ", ";
                        $qaprovada++;
// echo $i . " item " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2) . " " . $qaprovada . "<br> ";
                    endif;
                    /* Localiza as items modificadas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'modificada'):
// echo $i . "Modificada: ";
                        $modificada .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $modificada .= " ";
                        $modificada .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $modificada .= " ";
                        $modificada .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $modificada .= ", ";
                        $qmodificada++;
                    endif;
                    /* Localiza as items suprimidas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'suprimida'):
// echo $i . "Suprimida: ";
                        $suprimida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $suprimida .= " ";
                        $suprimida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $suprimida .= " ";
                        $suprimida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $suprimida .= ", ";
                        $qsuprimida++;
                    endif;
                    /* Localiza as items incluídas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'inclusão'):
// echo $i . "Inclusão: ";
                        $incluida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $incluida .= " ";
                        $incluida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $incluida .= " ";
                        $incluida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $incluida .= ", ";
                        $qincluida++;
                    endif;
                    /* Localiza as items minoritárias */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'minoritária'):
// echo $i . "Inclusão: ";
                        $minoritaria .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $minoritaria .= " ";
                        $minoritaria .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $minoritaria .= " ";
                        $minoritaria .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $minoritaria .= ", ";
                        $qminoritaria++;
                    endif;
                    /* Localiza as items remitidas */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'remitida'):
// echo $i . "Inclusão: ";
                        $remitida .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $remitida .= " ";
                        $remitida .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $remitida .= " ";
                        $remitida .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $remitida .= ", ";
                        $qremitida++;
                    endif;
                    /* Localiza as items otras */
                    if ($relatorio[$i][$t]['Votacao']['resultado'] == 'outra'):
// echo $i . "Inclusão: ";
                        $outra .= "Grupo " . $relatorio[$i][$t]['Votacao']['grupo'];
                        $outra .= " ";
                        $outra .= "item: " . substr($relatorio[$i][$t]['Votacao']['item'], 3, 2);
                        $outra .= " ";
                        $outra .= "(" . $relatorio[$i][$t]['Votacao']['votacao'] . ")";
                        $outra .= ", ";
                        $qoutra++;
                    endif;

                    $tr = $relatorio[$i][$t]['Votacao']['tr'];
// $situacao_nos_grupos[] = "<b>TR: " . $relatorio[$i][$t]['Votacao']['tr'] . "</b>. " . $aprovada . $modificada . $suprimida . $incluida . $minoritaria . "<br>";
// break;
                    /* Quais grupos trabalharam */
                    $grupos[] = $relatorio[$i][$t]['Votacao']['grupo'];

                endfor;

                /* Grupos que analisaram o item */
                if (!empty($grupos)):
// pr($grupos);          
                    $grupos_unicos = array_unique($grupos);
                    asort($grupos_unicos);
                    $grupos_total = implode(', ', $grupos_unicos);
                endif;
// die();
                $quantidade[] = "<b>Grupo(s):</b> " . $grupos_total . '<br>' . "<b>TR: " . $tr . "</b>. " . '<b>Aprovados:</b> ' . $qaprovada . ', <b>modificados:</b> ' . $qmodificada . ', <b>suprimidos:</b> ' . $qsuprimida . ', <b>incluídos:</b> ' . $qincluida . ', <b>minoritários:</b> ' . $qminoritaria . ', <b>remitidas:</b>' . $qremitida . ', <b>outras votações:</b> ' . $qoutra;

                $situacao_nos_grupos[] = "<b>TR: " . $tr . "</b>. " . $aprovada . $modificada . $suprimida . $incluida . $minoritaria . $remitida . $outra . "<br>";
            }

            $this->set('relatorio', $relatorio);
            $this->set('situacao', $situacao_nos_grupos);
            $this->set('quantidade', $quantidade);
        }

        if (empty($this->Auth->user('id'))):

// $this->Flash->error(__('Ação não autorizada.'));
            return $this->redirect(array('controller' => 'Items', 'action' => 'index'));

        endif;
    }

}
