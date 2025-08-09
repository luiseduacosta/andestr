<?php

App::uses("AppController", "Controller");

/**
 * Eventos Controller
 *
 * @property Evento $Evento
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class EventosController extends AppController
{
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = ["Session", "Html", "Form", "Text"];

    /**
     * Components
     *
     * @var array
     */
    public $components = ["Paginator", "Session", "Flash"];

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['index', 'view']); // Allow public access to these actions
        $this->set("usuario", $this->Auth->user());
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Paginator->settings = [
            "order" => ["ordem" => "asc"],
        ];
        $this->set("eventos", $this->Paginator->paginate());
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
        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__("Invalid evento"));
        }
        /** Gravo o evento selecionado */
        $this->Session->write("evento_id", $id);
        $this->Evento->contain([
            "Apoio" => [
                "order" => ["Apoio.evento_id" => "asc", "Apoio.numero_texto" => "asc"],
                "Gt"
            ]
        ]);
        $options = [
            'contains' => [
                'Apoio' => [
                    'order' => ['Apoio.evento_id' => 'asc', 'Apoio.numero_texto' => 'asc']
                ],
                'Gt'
            ],
            "conditions" => ["Evento." . $this->Evento->primaryKey => $id],
        ];
        $this->set("evento", $this->Evento->find("first", $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->Auth->user('role') === 'admin' || $this->Auth->user('role') === 'editor') {
            // User has permission to add events
        } else {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        /** Envio o numero de ordem para o formulário */
        $this->Evento->contain();
        $this->set(
            "evento",
            $this->Evento->find("first", ["order" => ["ordem" => "desc"]]),
        );
        if ($this->request->is("post")) {
            $this->Evento->create();
            if ($this->Evento->save($this->request->data)) {
                $this->Flash->success(__("Evento cadastrado."));
            } else {
                $this->Flash->error(
                    __("Não foi possível cadastrar o evento. Tente novamente."),
                );
            }
            return $this->redirect(["action" => "index"]);
        }
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
        if ($this->Auth->user('role') !== 'admin' && $this->Auth->user('role') !== 'editor') {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__("Evento não encontrado"));
        }
        if ($this->request->is(["post", "put"])) {
            if ($this->Evento->save($this->request->data)) {
                $this->Flash->success(__("Evento atualizado."));
            } else {
                $this->Flash->error(
                    __("Evento não foi atualizado. Tente novamente."),
                );
            }
            return $this->redirect(["action" => "view", $id]);
        } else {
            $options = [
                "conditions" => ["Evento." . $this->Evento->primaryKey => $id],
            ];
            $this->request->data = $this->Evento->find("first", $options);
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
        if ($this->Auth->user('role') !== 'admin' && $this->Auth->user('role') !== 'editor') {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__("Evento não encontrado"));
        }
        $this->request->allowMethod("post", "delete");
        if ($this->Evento->delete($id)) {
            $this->Flash->success(__("Evento excluído."));
        } else {
            $this->Flash->error(
                __("Não foi possível excluir o evento. Tente novamente."),
            );
            return $this->redirect(["action" => "view", $id]);

        }
        return $this->redirect(["action" => "index"]);
    }
}
