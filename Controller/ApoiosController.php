<?php

App::uses("AppController", "Controller");

/**
 * Apoios Controller
 *
 * @property Apoio $Apoio
 * @property Gt $Gt
 * @property Evento $Evento
 * @property Item $Item
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * 
 */
class ApoiosController extends AppController
{
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = ["Session", "Html", "Form", "Text", "Paginator"];

    /**
     * Components
     *
     * @var array
     */
    public $components = ["Paginator", "Session"];

    /**
     * beforeFilter method
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['index', 'view', 'apoiocompleto', 'busca']); // Allow public access to these actions
        $this->set("usuario", $this->Auth->user());
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        // Get the event ID from query or session
        $evento_id = isset($this->request->query["evento_id"])
            ? $this->request->query["evento_id"]
            : $this->Session->read("evento_id");

        /** Lista todos os eventos */
        $this->loadModel("Evento");
        $eventos = $this->Evento->find("list", [
            "fields" => ["id", "nome"],
            "order" => ["ordem" => "asc"],
        ]);
        /** Se evento não veio como parametro nem como cookie então seleciono o último evento */
        if (empty($evento_id)) {
            end($eventos); /** o ponteiro está no último registro */
            $evento_id = key($eventos);
        }
        /** Gravo um cookie com o evento_id */
        $this->Session->write("evento_id", $evento_id);

        $this->Paginator->settings = [
            "Apoio" => [
                "conditions" => ["Apoio.evento_id" => $evento_id],
                "order" => ["Apoio.numero_texto" => "asc"],
            ],
        ];

        $this->set("apoios", $this->Paginator->paginate());
        $this->set("evento_id", $evento_id);
        $this->set(
            "gts",
            $this->Apoio->find("list", [
                "fields" => ["Gt.id", "Gt.sigla"],
                "order" => ["Gt.sigla" => "asc"],
                "contain" => ["Gt"],
            ]),
        );
        $this->set("eventos", $eventos);
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
            throw new NotFoundException(__("Texto de apoio não encontrado"));
        }
        $options = [
            "conditions" => ["Apoio." . $this->Apoio->primaryKey => $id],
        ];
        $this->set("apoio", $this->Apoio->find("first", $options));
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
            throw new NotFoundException(__("Texto de apoio não encontrado"));
        }
        $options = [
            "contain" => ["Evento", "Gt"],
            "conditions" => ["Apoio." . $this->Apoio->primaryKey => $id],
        ];
        $this->set("apoio", $this->Apoio->find("first", $options));
    }

    /**
     * busca method - Search through Apoios
     *
     * @return void
     */
    public function busca()
    {
        // Get the event ID from query or session
        $evento_id = isset($this->request->query["evento_id"]) 
            ? $this->request->query["evento_id"]
            : $this->Session->read("evento_id");

        // Initialize conditions array
        $conditions = [];

        // Add event condition if event_id exists
        if (!empty($evento_id)) {
            $conditions['Apoio.evento_id'] = $evento_id;
        }

        // Check if there's a search term
        if (!empty($this->request->query['termo'])) {
            $termo = $this->request->query['termo'];
            $conditions['OR'] = [
                'Apoio.titulo LIKE' => '%' . $termo . '%',
                'Apoio.texto LIKE' => '%' . $termo . '%',
                'Apoio.autor LIKE' => '%' . $termo . '%'
            ];
        }

        // Configure pagination
        $this->Paginator->settings = [
            'Apoio' => [
                'conditions' => $conditions,
                'limit' => 20,
                'order' => ['Apoio.numero_texto' => 'asc'],
                'contain' => ['Gt', 'Evento']
            ]
        ];

        // Get all events for the dropdown
        $this->loadModel("Evento");
        $eventos = $this->Evento->find("list", [
            "fields" => ["id", "nome"],
            "order" => ["ordem" => "asc"]
        ]);

        // Set variables for the view
        $this->set('apoios', $this->Paginator->paginate());
        $this->set('eventos', $eventos);
        $this->set('evento_id', $evento_id);
        $this->set('termo', isset($this->request->query['termo']) ? $this->request->query['termo'] : '');
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['admin', 'editor'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }
        // Get the event ID from query or session
        $evento_id = isset($this->request->query["evento_id"])
            ? $this->request->query["evento_id"]
            : $this->Session->read("evento_id");
        $this->loadModel("Evento");
        $eventos = $this->Evento->find("list", [
            "fields" => ["id", "nome"],
            "order" => ["ordem" => "asc"],
        ]);
        if (empty($eventos)) {
            $this->Flash->error(
                __(
                    "Não há eventos cadastrados. Cadastre um evento antes de cadastrar um Texto de Apoio.",
                ),
            );
            return $this->redirect([
                "controller" => "Eventos",
                "action" => "add",
            ]);
        }
        if (empty($evento_id)) {
            $evento_id = end($eventos);
        }
        if ($evento_id) {
            /** Envio para o formulário */
            $this->set("evento_id", $evento_id);
        } else {
            $this->Flash->error(
                __("Não foi possível selecionar o evento. Tente novamente."),
            );
            return $this->redirect(["action" => "index"]);
        }

        if ($this->request->is("post")) {
            /** Verifica se já está cadastrado */
            $this->Apoio->contain();
            $apoio = $this->Apoio->find("first", [
                "conditions" => [
                    "and" => [
                        "Apoio.numero_texto" =>
                            $this->request->data["Apoio"]["numero_texto"],
                        "Apoio.evento_id" =>
                            $this->request->data["Apoio"]["evento_id"],
                    ],
                ],
            ]);
            if ($apoio) {
                $this->Flash->error(
                    __(
                        "Já há um Texto de Apoio com essa numeração no evento. Verifique e tente novamente.",
                    ),
                );
            } else {
                /** Elimina os retornos e as novas linhas do texto original */
                $this->request->data["Apoio"]["autor"] = str_replace(
                    ["<br />", "<br>"],
                    "",
                    $this->request->data["Apoio"]["autor"],
                );
                $this->request->data["Apoio"]["texto"] = str_replace(
                    ["\r", "\n"],
                    " ",
                    $this->request->data["Apoio"]["texto"],
                );

                /** Preenche o campo nomedovento */
                if ($this->request->data['Apoio']['evento_id']) {
                    $this->loadModel('Evento');
                    $evento = $this->Evento->find('first', [
                        'conditions' => ['Evento.id' => $this->request->data['Apoio']['evento_id']]
                    ]);
                } else {
                    $this->Flash->error(__('Selecione o evento'));
                }
                if ($evento) {
                    $this->request->data['Apoio']['nomedoevento'] = $evento['Evento']['nome'];
                }

                /** Prenche o campo gt */
                if ($this->request->data['Apoio']['gt_id']) {
                    $this->loadModel('Gt');
                    $grupodetrabalho = $this->Gt->find('first', [
                        'conditions' => ['Gt.id' => $this->request->data['Apoio']['gt_id']]
                    ]);
                } else {
                    $this->Flash->error(__('Selecione um Grupo de Trabalho ou Setor'));
                }
                if ($grupodetrabalho) {
                    $this->request->data['Apoio']['gt'] = $grupodetrabalho['Gt']['sigla'];
                }

                $this->Apoio->create();
                if ($this->Apoio->save($this->request->data)) {
                    $this->Flash->success(__("Texto de apoio inserido."));
                    return $this->redirect([
                        "action" => "view",
                        $this->Apoio->getLastInsertId(),
                    ]);
                } else {
                    $this->Flash->error(
                        __(
                            "Não foi possível inserir o Texto de Apoio. Tente novamente.",
                        ),
                    );
                }
            }
        }
        $this->set(
            "gts",
            $this->Apoio->find("list", [
                "fields" => ["Gt.id", "Gt.sigla"],
                "order" => ["Gt.sigla" => "asc"],
                "contain" => ["Gt"],
            ]),
        );
        $this->set("eventos", $eventos);
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
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['admin', 'editor'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__("Texto de Apoio não encontrado"));
        }
        if ($this->request->is(["post", "put"])) {
            $this->request->data["Apoio"]["autor"] = str_replace(
                ["<br />", "<br"],
                "",
                $this->request->data["Apoio"]["autor"],
            );
            $this->request->data["Apoio"]["autor"] = str_replace(
                ["\r", "\n"],
                " ",
                $this->request->data["Apoio"]["autor"],
            );
            $this->request->data["Apoio"]["texto"] = str_replace(
                ["<br />", "<br>"],
                "",
                $this->request->data["Apoio"]["texto"],
            );
            $this->request->data["Apoio"]["texto"] = str_replace(
                ["\r", "\n"],
                " ",
                $this->request->data["Apoio"]["texto"],
            );

            /** Preenche o campo nomedovento */
            if ($this->request->data['Apoio']['evento_id']) {
                $this->loadModel('Evento');
                $evento = $this->Evento->find('first', [
                    'conditions' => ['Evento.id' => $this->request->data['Apoio']['evento_id']]
                ]);
            } else {
                $this->Flash->error(__('Selecione o evento'));
            }
            if ($evento) {
                $this->request->data['Apoio']['nomedoevento'] = $evento['Evento']['nome'];
            }

            /** Preenche o campo gt */
            if ($this->request->data['Apoio']['gt_id']) {
                $this->loadModel('Gt');
                $grupodetrabalho = $this->Gt->find('first', [
                    'conditions' => ['Gt.id' => $this->request->data['Apoio']['gt_id']]
                ]);
            } else {
                $this->Flash->error(__('Selecione um Grupo de Trabalho ou Setor'));
            }
            if ($grupodetrabalho) {
                $this->request->data['Apoio']['gt'] = $grupodetrabalho['Gt']['sigla'];
            }

            if ($this->Apoio->save($this->request->data)) {
                $this->Flash->success(__("Texto de Apoio atualizado."));
                return $this->redirect(["action" => "view", $id]);
            } else {
                $this->Flash->error(
                    __(
                        "Não foi possível atualizar o Texto de Apoio. Tente novamente.",
                    ),
                );
            }
        } else {
            $options = [
                "conditions" => ["Apoio." . $this->Apoio->primaryKey => $id],
            ];
            $this->set("apoio", $this->Apoio->find("first", $options));
            $this->set(
                "gts",
                $this->Apoio->find("list", [
                    "contain" => ["Gt"],
                    "fields" => ["Gt.id", "Gt.sigla"],
                    "order" => ["Gt.sigla" => "asc"],
                ]),
            );
            $this->set(
                "eventos",
                $this->Apoio->find("list", [
                    "contain" => ["Evento"],
                    "fields" => ["Evento.id", "Evento.nome"],
                    "order" => ["Evento.ordem" => "asc"],
                ]),
            );
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
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['admin', 'editor'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }
 
        if (!$this->Apoio->exists($id)) {
            throw new NotFoundException(__("Texto de Apoio não encontrado"));
        }
        $this->request->allowMethod("post", "delete");

        if ($this->Apoio->delete($id)) {
            $this->Flash->success(__("Texto de Apoio excluído."));
        } else {
            $this->Flash->error(
                __(
                    "Não foi possível excluir o Texto de Apoio. Tente novamente.",
                ),
            );
            return $this->redirect(["action" => "view", $id]);
        }
        return $this->redirect(["action" => "index"]);
    }

    /**
     * collation method
     *
     * @return void
     */
    public function collation()
    {
        if ($this->Auth->user()) {
            $this->Auth->user('role') === 'admin';
        } else {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        $this->autoRender = false;
        $apoios = $this->Apoio->find("all");

        foreach ($apoios as $apoio) {
            $apoionovo["Apoio"]["id"] = $apoio["Apoio"]["id"];
            $apoionovo["Apoio"]["nomedoevento"] =
                $apoio["Apoio"]["nomedoevento"];
            $apoionovo["Apoio"]["evento_id"] = $apoio["Apoio"]["evento_id"];
            $apoionovo["Apoio"]["caderno"] = $apoio["Apoio"]["caderno"];
            $apoionovo["Apoio"]["numero_texto"] =
                $apoio["Apoio"]["numero_texto"];
            $apoionovo["Apoio"]["tema"] = $apoio["Apoio"]["tema"];
            $apoionovo["Apoio"]["gt"] = $apoio["Apoio"]["gt"];
            $apoionovo["Apoio"]["gt1"] = $apoio["Apoio"]["gt"];
            $apoionovo["Apoio"]["titulo"] = isset($apoio["Apoio"]["titulo"])
                ? $apoio["Apoio"]["titulo"]
                : "";
            $apoionovo["Apoio"]["titulo1"] = isset($apoio["Apoio"]["titulo"])
                ? $apoio["Apoio"]["titulo"]
                : "";
            $apoionovo["Apoio"]["autor"] = str_replace(
                ["\r", "\n"],
                "",
                $apoio["Apoio"]["autor"],
            );
            $apoionovo["Apoio"]["autor1"] = str_replace(
                ["\r", "\n"],
                "",
                $apoio["Apoio"]["autor"],
            );
            $apoionovo["Apoio"]["texto"] = str_replace(
                ["\r", "\n"],
                "",
                $apoio["Apoio"]["texto"],
            );
            $apoionovo["Apoio"]["texto1"] = str_replace(
                ["\r", "\n"],
                "",
                $apoio["Apoio"]["texto"],
            );

            if ($this->Apoio->save($apoionovo, ["validate" => false])) {
            } else {
                // $log = $this->Apoio->getDataSource()->getLog(false, false);
                // debug($log);
                $errors = $this->Apoio->invalidFields();
                // pr($errors);
                // pr($this->Apoio->validationErrors);
                $this->Flash->error(
                    __(
                        "Não foi possível atualizar o texto de apoio " .
                        $apoio["Apoio"]["id"] .
                        ". Tente novamente.",
                    ),
                );
                // die();
            }
        }
    }
}
