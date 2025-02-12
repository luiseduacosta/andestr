<?php
App::uses('AppController', 'Controller');
/**
 * Gts Controller
 *
 * @property Gt $Gt
 * @property PaginatorComponent $Paginator
 */
class GtsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = ['Paginator'];

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Gt->contain();
		$this->set('gts', $this->Paginator->paginate());
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
			throw new NotFoundException(__('Parâmetro do GT errado'));
		}
		$options = ['conditions' => ['Gt.' . $this->Gt->primaryKey => $id]];
		$this->set('gt', $this->Gt->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->Gt->create();
			if ($this->Gt->save($this->request->data)) {
				$this->Flash->success(__('GT inserido.'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('GT não foi inserido. Tente novamente.'));
			}
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
		if (!$this->Gt->exists($id)) {
			throw new NotFoundException(__('Parâmetro do GT errado'));
		}
		if ($this->request->is(['post', 'put'])) {
			if ($this->Gt->save($this->request->data)) {
				$this->Flash->success(__('GT atualizado.'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('GT não foi atualizado. Tente novamente.'));
			}
		} else {
			$options = ['conditions' => ['Gt.' . $this->Gt->primaryKey => $id]];
			$this->request->data = $this->Gt->find('first', $options);
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
		if (!$this->Gt->exists($id)) {
			throw new NotFoundException(__('Parâmetro do GT errado'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Gt->delete($id)) {
			$this->Flash->success(__('GT excluído.'));
		} else {
			$this->Flash->error(__('GT não foi excluído. Tente novamente.'));
		}
		return $this->redirect(['action' => 'index']);
	}
}
