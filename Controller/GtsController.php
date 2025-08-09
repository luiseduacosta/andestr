<?php
App::uses("AppController", "Controller");

/**
 * Gts Controller
 *
 * @property Gt $Gt
 * @property PaginatorComponent $Paginator
 */
class GtsController extends AppController
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
    public $components = ["Paginator"];

    /**
     * Before filter method
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['index', 'view']); // Allow public access to these actions
        // Set the logged user and site title for the view
        $this->set("usuario", $this->Auth->user());
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Gt->contain();
        $this->set("gts", $this->Paginator->paginate());
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
        if (!$this->Gt->exists($id)) {
            throw new NotFoundException(__("GT não encontrado"));
        }
        $options = ["conditions" => ["Gt." . $this->Gt->primaryKey => $id]];
        $this->set("gt", $this->Gt->find("first", $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['editor', 'admin'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }
        if ($this->request->is("post")) {
            $this->Gt->create();
            if ($this->Gt->save($this->request->data)) {
                $this->Flash->success(__("GT cadastrado."));
            } else {
                $this->Flash->error(
                    __("Não foi possível cadastrar o GT. Tente novamente."),
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
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['editor', 'admin'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        if (!$this->Gt->exists($id)) {
            throw new NotFoundException(__("GT não encontrado"));
        }

        if ($this->request->is(["post", "put"])) {
            if ($this->Gt->save($this->request->data)) {
                $this->Flash->success(__("GT atualizado."));
                return $this->redirect(["action" => "view", $id]);
            } else {
                $this->Flash->error(
                    __("Não foi possível atualizar o GT. Tente novamente."),
                );
                return $this->redirect(["action" => "index"]);
            }
        } else {
            $options = ["conditions" => ["Gt." . $this->Gt->primaryKey => $id]];
            $this->request->data = $this->Gt->find("first", $options);
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
        if (!$this->Auth->user() || !in_array($this->Auth->user('role'), ['editor', 'admin'])) {
            throw new ForbiddenException(__('Acesso negado.'));
        }

        if (!$this->Gt->exists($id)) {
            throw new NotFoundException(__("GT não encontrado"));
        }
        $this->request->allowMethod("post", "delete");
        if ($this->Gt->delete($id)) {
            $this->Flash->success(__("GT excluído."));
        } else {
            $this->Flash->error(
                __("Não foi possível excluir o GT. Tente novamente."),
            );
            return $this->redirect(["action" => "view", $id]);
        }
        return $this->redirect(["action" => "index"]);
    }
}
