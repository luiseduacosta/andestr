<?php

App::uses("AppController", "Controller");

/**
 * Items Controller
 * 
 * @property Evento $Evento
 * @property Item $Item
 * @property User $User
 * @property Apoio $Apoio
 * @property Votacao $Votacao
 * 
 */
class ItemsController extends AppController
{
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = ['Session', 'Html', 'Form', 'Text'];
    /**
     * Components
     *
     * @var array
     */
    public $components = ["Paginator", "Session"];

    public function isAuthorized($user)
    {
        if (isset($user["role"]) && $user["role"] == "editor") {
            return true;
        }

        // All registered users can add posts
        if ($this->action === "add") {
            return true;
        }

        return parent::isAuthorized($user);
    }

    function beforeFilter()
    {
        parent::beforeFilter();

        $usuario = $this->Auth->user();
        if (isset($usuario) && $usuario["role"] == "relator"):
            if (strlen($usuario["username"]) == 6):
                $usuariogrupo = substr($usuario["username"], 5, 1);
            elseif (strlen($usuario["username"]) == 7):
                $usuariogrupo = substr($usuario["username"], 5, 2);
            endif;
            $this->set("usuariogrupo", $usuariogrupo);
        endif;
        $this->set("usuario", $usuario);
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $apoio_id = isset($this->request->query["apoio_id"])
            ? $this->request->query["apoio_id"]
            : null;
        $tr = isset($this->request->query["tr"])
            ? $this->request->query["tr"]
            : null;
        $evento_id = isset($this->request->query["evento_id"])
            ? $this->request->query["evento_id"]
            : $this->Session->read("evento_id");
        $grupo = isset($this->request->query["grupo"])
            ? $this->request->query["grupo"]
            : null;

        /** Para fazer a lista dos eventos */
        $this->loadModel("Evento");
        $eventos = $this->Evento->find("list", [
            "order" => ["ordem" => "asc"],
        ]);

        /** Se evento_id não veio como parametro nem pode ser calculado a partir do apoio_id então seleciono o último evento */
        if (empty($evento_id)) {
            end($eventos); // o ponteiro está no último registro
            $evento_id = key($eventos);
        }

        /** Gravo o cookei com o evento_id */
        if ($evento_id) {
            $this->Session->write("evento_id", $evento_id);
        }

        $this->loadModel("Apoio");
        if (isset($this->request->query["tr"]) && isset($evento_id)):
            $tr = $this->request->query["tr"];
            $this->Paginator->settings = [
                "Item" => [
                    "conditions" => [
                        "Apoio.evento_id" => $evento_id,
                        "Item.tr" => $tr,
                    ],
                    "order" => ["item" => "asc"],
                ],
            ];
        else:
            $this->Paginator->settings = [
                "Item" => [
                    "conditions" => ["Apoio.evento_id" => $evento_id],
                    "order" => ["item" => "asc"],
                ],
            ];
        endif;

        $this->set("items", $this->Paginator->paginate());

        /** Para fazer a lista das TRs na coluna lateral */
        $this->Item->contain(['Apoio']);
        $tresolucao = $this->Item->find("all", [
            "conditions" => ["Apoio.evento_id" => $evento_id],
            "fields" => ["Item.tr"],
            "group" => ["Item.tr"],
        ]);

        $this->set("grupo", isset($grupo) ? $grupo : null);
        $this->set("tr", $tresolucao);
        $this->set("evento_id", $evento_id);
        $this->set("eventos", $eventos);
    }

    /**
     * view method
     *
     * @param string|null $id Item id.
     * @return void
     * @throws NotFoundException When item does not exist.
     */
    public function add()
    {
        /**
         * @var mixed $evento_id
         * Captura o evento_id do querystring ou da sessão
         * Se não houver, pega o último evento cadastrado
         * e o coloca no cookie da sessão.
         * Se não houver evento cadastrado, redireciona para a tela de cadastro de Apoios
         */
        $evento_id = isset($this->request->query["evento_id"])
            ? $this->request->query["evento_id"]
            : $this->Session->read("evento_id");
        /**
         * @var array $eventos
         * Captura todos os eventos cadastrados
         * Se não houver eventos cadastrados, redireciona para a tela de cadastro de Eventos
         */
        $this->loadModel("Evento");
        $eventos = $this->Evento->find("list", [
            "order" => ["id" => "asc"],
        ]);
        if (empty($eventos)) {
            $this->Flash->error(__("Não há eventos cadastrados!"));
            return $this->redirect([
                "controller" => "Eventos",
                "action" => "add",
            ]);
        }
        if (empty($evento_id)) {
            end($eventos); // o ponteiro está no último registro
            $evento_id = key($eventos);
        }

        /** Captura o apoio_id do querystring se houver */
        $apoio_id = isset($this->request->query["apoio_id"])
            ? $this->request->query["apoio_id"]
            : null;

        /** Localiza se há TRs */
        $this->loadModel("Apoio");
        if ($apoio_id) {
            $apoios = $this->Apoio->find("all", [
                "conditions" => ["Apoio.evento_id" => $evento_id, "Apoio.id" => $apoio_id],
                "order" => ["numero_texto" => "desc"],
            ]);
        } else {
            $apoios = $this->Apoio->find("all", [
                "conditions" => ["Apoio.evento_id" => $evento_id],
                "order" => ["numero_texto" => "desc"],
            ]);
            if (empty($apoios)) {
                $this->Flash->error(__("Não há textos de apoio nem TRs cadastrados!"));
                return $this->redirect([
                    "controller" => "Apoios",
                    "action" => "add",
                ]);
            }
        }
        $apoioslista = $this->Apoio->find("list");

        /** Para aumentar a numeração dos items da TR */
        if ($apoios) {
            if ($apoio_id) {
                $ultimo = $apoios[0];
            } else {
                $ultimo = end($apoios);
            }
            $ultimo_tr = $ultimo["Apoio"]["numero_texto"];
            if (strlen($ultimo_tr) == 1) {
                $ultimo_tr = "0" . $ultimo_tr;
            }
            $items = $this->Item->find("all", [
                "conditions" => ["apoio_id" => $ultimo["Apoio"]["id"]],
            ]);
            $ultimo_item = end($items);

            /** Dividir o item e aumentar em + 1 para o próximo */
            if ($ultimo_item) {
                $ultimoItem = $ultimo_item["Item"]["item"];
                $itemparcela = explode(".", $ultimoItem);
                $itemparcela_tr = $itemparcela[0] + 1;
                $itemparcela_item = $itemparcela[1] + 1;
                if (strlen($itemparcela_tr) == 1) {
                    $itemparcela_tr = "0" . $itemparcela_tr;
                } else {
                    $itemparcela_tr;
                }
                if (strlen($itemparcela_item) == 1) {
                    $itemparcela_item = "0" . $itemparcela_item;
                } else {
                    $itemparcela_item;
                }
            }
        } else {
            $itemparcela_tr = "01";
            $itemparcela_item = "01";
        }
        // pr($itemparcela_tr);
        // pr($itemparcela_item);

        /** Envio para o formulário */
        $this->set("evento_id", $evento_id);
        $this->set("ultimo_tr", isset($ultimo_tr) ? $ultimo_tr : "01");
        $this->set(
            "item_item",
            isset($itemparcela_item) ? $itemparcela_item : "01",
        );
        $this->set("apoio_id", $ultimo["Apoio"]["id"]);
        $this->set("apoios", $apoioslista);

        if ($this->request->is("post")) {
            // pr($this->request->data);
            // Capturo o id corespondente ao TR do apoio do evento
            $apoio = $this->Apoio->find("first", [
                "conditions" => [
                    "numero_texto" => $this->request->data["Item"]["tr"],
                    "evento_id" => $evento_id,
                ],
            ]);
            $this->request->data["Item"]["apoio_id"] = $apoio["Apoio"]["id"];
            // Elimina os \r e \n e <br /> do texto original
            $this->request->data["Item"]["texto"] = str_replace(
                ["\r", "\n"],
                "",
                $this->request->data["Item"]["texto"],
            );
            $this->request->data["Item"]["texto"] = str_replace(
                ["<br />", "<br>"],
                " ",
                $this->request->data["Item"]["texto"],
            );
            // A partir do Tr busco o id na tabela Items
            if ($this->request->data['Item']['apoio_id']) {
                $this->loadModel("Apoio");
                $verifica_tr = $this->Apoio->find('first', [
                    'conditions' => [
                        'Apoio.id' => $this->request->data['Item']['apoio_id'],
                    ],
                ]);
                /**
                 * Verifica que o item e o Tr estejam coordenados
                 */
                if ($verifica_tr):
                    if (
                        $verifica_tr["Apoio"]["numero_texto"] !=
                        substr($this->request->data["Item"]["item"], 0, 2)
                    ):
                        // echo $verifica_tr['Apoio']['numero_texto'] . " " . substr($this->request->data['Item']['item'], 0, 2);
                        $this->Flash->error(
                            __(
                                "Os dois primeiros números do item devem corresponder com o TR",
                            ),
                        );
                        return $this->redirect([
                            "controller" => "items",
                            "action" => "add",
                        ]);
                    else:
                        $this->request->data["Item"]["tr"] =
                            $verifica_tr["Apoio"]["numero_texto"];
                    endif;
                else:
                    $this->Flash->error(
                        __(
                            "O Texto de Apoio do item não existe. Insira uma nova TR começando pelo texto de apoio",
                        ),
                    );
                    return $this->redirect([
                        "controller" => "apoios",
                        "action" => "add",
                    ]);
                endif;
            }
            if ($this->request->data["Item"]["item"] == "") {
                $this->Flash->error(__("O campo item não pode ser vazio!"));
                return $this->redirect([
                    "controller" => "items",
                    "action" => "add",
                ]);
            } else {
                $item = substr($this->request->data["Item"]["item"], 3, 2);
                /** Caso de uma inserção de um item novo */
                if ($item == "00") {
                    $apoio = $this->Apoio->find("first", [
                        "conditions" => [
                            "numero_texto" =>
                                $this->request->data["Item"]["tr"],
                            "evento_id" => $evento_id,
                        ],
                    ]);
                    if ($apoio) {
                        /** Capturo o último item */
                        $this->loadModel("Items");
                        $item = $this->Item->find("first", [
                            "conditions" => [
                                "Items.apoio_id" => $apoio["Apoios"]["id"],
                            ],
                            "order" => ["Items.item" => "desc"],
                        ]);
                        $valor_item = explode(".", $item["Items"]["item"]);
                        $valor_item = $valor_item[1] + 1;
                        if (strlen($valor_item) == 1) {
                            $valor_item = "0" . $valor_item;
                        }
                        $this->request->data["Item"]["item"] =
                            $this->request->data["Item"]["tr"] .
                            "." .
                            $valor_item;
                    }
                    $this->request->data["Item"]["apoio_id"] =
                        $apoio["Apoios"]["id"];
                }
            }

            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                // pr($this->request->data);
                // die();
                $this->Flash->success(__("Item inserido."));
                return $this->redirect([
                    "controller" => "Items",
                    "action" => "view",
                    $this->Item->getLastInsertId(),
                ]);
            } else {
                $this->Flash->error(
                    __("Item não foi inserido. Tente novamente."),
                );
            }
        }

        $this->Item->contain(["Apoio"]);
        $tr = $this->Item->find("list", [
            "fields" => ["Apoio.numero_texto"],
            "conditions" => ["Apoio.evento_id" => $evento_id],
        ]);
        if (empty($tr)) {
            $this->Flash->error(__("Não há textos de resolução cadastrados!"));
            return $this->redirect([
                "controller" => "Apoios",
                "action" => "index",
            ]);
        }

        $this->set("tr", $tr);
        $this->set("eventos", $eventos);
        $this->set("evento_id", $evento_id);
    }

    /**
     * view Method
     * 
     * @param string|null $id Item id.
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__("Item não localizado."));
        }

        if ($this->Auth->user("id")):
            $categoria = $this->autenticausuario();
        endif;

        $votacao = isset($this->request->query["votacao"])
            ? $this->request->query["votacao"]
            : 0;
        $this->set("votacao", $votacao);

        $options = ["contain" => ['Apoio' => ['Evento'], 'Votacao'], "conditions" => ["Item." . $this->Item->primaryKey => $id]];
        $this->set("item", $this->Item->find("first", $options));
    }

    /**
     * edit method
     *
     * @param string|null $id Item id.
     * @return void
     * @throws NotFoundException When item does not exist.
     */
    public function edit($id = null)
    {
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__("Item não localizado"));
        }
        if ($this->request->is(["post", "put"])) {
            // Elimina os \r e \n e <br /> do texto original
            $this->request->data["Item"]["texto"] = str_replace(
                ["\r", "\n"],
                "",
                $this->request->data["Item"]["texto"],
            );
            $this->request->data["Item"]["texto"] = str_replace(
                ["<br />"],
                " ",
                $this->request->data["Item"]["texto"],
            );

            if ($this->Item->save($this->request->data)) {
                $this->Flash->success(__("Item atualizado."));
                return $this->redirect([
                    "controller" => "Items",
                    "action" => "view",
                    $this->request->data["Item"]["id"],
                ]);
            } else {
                $this->Flash->error(
                    __("Item não foi inserido. Tente novamente."),
                );
            }
        } else {
            $options = [
                "conditions" => ["Item." . $this->Item->primaryKey => $id],
            ];
            $this->request->data = $this->Item->find("first", $options);
        }

        $options = ["conditions" => ["Item." . $this->Item->primaryKey => $id]];
        $resolucaos = $this->Item->find("first", $options);

        $this->set("resolucaos", $resolucaos);
    }

    /**
     * delete method
     *
     * @param string|null $id Item id.
     * @return void
     * @throws NotFoundException When item does not exist.
     */
    public function delete($id = null)
    {
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__("Item não localizado"));
        }

        // Capturo o valor do campo resolucao_id para ir para a TR do item
        $resolucao = $this->Item->findById($id);

        $this->request->allowMethod("post", "delete");

        if ($this->Item->delete($id)) {
            $this->Flash->success(__("Item excluído."));
        } else {
            $this->Flash->error(__("Item não foi excluído. Tente novamente."));
        }
        return $this->redirect([
            "controller" => "items",
            "action" => "index",
            "?" => ["apoio_id" => $resolucao["Item"]["apoio_id"]],
        ]);
    }

    /**
     * Método que corrige a numeração do campo item
     */
    public function atualiza()
    {
        $this->Item->contain();
        $items = $this->Item->find("all");

        $i = 1;
        foreach ($items as $c_item):
            /** Corrige a numeração dos items */
            if (substr($c_item["Item"]["item"], 3, 2) == "00") {
                // pr($c_item['Item']['item']);
                $tr = substr($c_item["Item"]["item"], 0, 2);
                if ($i == 1) {
                    $resolucao = $tr;
                }
                if ($tr == $resolucao) {
                    if (strlen($i) == 1) {
                        $i = "0" . $i;
                    }
                    $item = $tr . "." . $i;
                    // pr($c_item['Item']['id']);
                    $c_item["Item"]["item"] = $item;
                    $i++;
                } else {
                    $i = 1;
                    if (strlen($i) == 1) {
                        $i = "0" . $i;
                    }
                    $item = $tr . "." . $i;
                    $c_item["Item"]["item"] = $item;
                    // pr($c_item['Item']['item']);
                    $resolucao = $tr;
                    $i++;
                }
                // pr($resolucao);
                // pr($tr);
                // pr($c_item);
                // pr($i);
                if ($this->Item->save($c_item["Item"])) {
                    $this->Flash->success(__("Item atualizado."));
                } else {
                    debug($this->Item->validationErrors);
                    die();
                }
            }

            /** Corrige o item em função do tamanho maior */
            if (strlen($c_item["Item"]["item"]) > 5) {
                // pr($c_item['Item']['item']);
                $tr = substr($c_item["Item"]["item"], 0, 2);
                if ($i == 1) {
                    $resolucao = $tr;
                }
                if ($tr == $resolucao) {
                    if (strlen($i) == 1) {
                        $i = "0" . $i;
                    }
                    $item = $tr . "." . $i;
                    // pr($c_item['Item']['id']);
                    $c_item["Item"]["item"] = $item;
                    $i++;
                } else {
                    $i = 1;
                    if (strlen($i) == 1) {
                        $i = "0" . $i;
                    }
                    $item = $tr . "." . $i;
                    $c_item["Item"]["item"] = $item;
                    // pr($c_item['Item']['item']);
                    $resolucao = $tr;
                    $i++;
                }
                // pr($resolucao);
                // pr($tr);
                // pr($c_item);
                // pr($i);
                if ($this->Item->save($c_item["Item"])) {
                    $this->Flash->success(__("Item atualizado."));
                } else {
                    debug($this->Item->validationErrors);
                    die();
                    // $this->Flash->error(__('Tente novamente.'));
                }
            }
        endforeach;
    }

    public function seleciona_lista()
    {
        $items = $this->Item->find("list", [
            "fields" => ["id", "item", "texto"],
        ]);
        // pr($items);
        // die();
        $this->set("items", $items);
    }

    public function collation()
    {
        $items = $this->Item->find("all");

        foreach ($items as $item) {
            // pr($item['Item']['texto']);
            $itemnovo["Item"]["id"] = $item["Item"]["id"];
            $itemnovo["Item"]["apoio_id"] = $item["Item"]["apoio_id"];
            $itemnovo["Item"]["tr"] = $item["Item"]["tr"];
            $itemnovo["Item"]["item"] = $item["Item"]["item"];
            $itemnovo["Item"]["texto"] = str_replace(
                ["\r", "\n"],
                "",
                $item["Item"]["texto"],
            );
            $itemnovo["Item"]["texto1"] = str_replace(
                ["\r", "\n"],
                "",
                $item["Item"]["texto"],
            );
            // pr($apoionovo['Apoio']['texto1']);

            if ($this->Item->save($itemnovo)) {
                $this->Flash->success(__("Item atualizado."));
            } else {
                $this->Flash->error(
                    __(
                        "Não foi possível atualizar o item " .
                        $item["Item"]["id"] .
                        ". Tente novamente.",
                    ),
                );
            }
        }
    }
}
