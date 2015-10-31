<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Devices Controller
 *
 * @property \App\Model\Table\DevicesTable $Devices
 */
class DevicesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('devices', $this->paginate($this->Devices));
        $this->set('_serialize', ['devices']);
    }

    /**
     * View method
     *
     * @param string|null $id Device id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $device = $this->Devices->get($id, [
            'contain' => []
        ]);

        $device->softwares = [ /* TODO */ ];
        $this->set('device', $device);
        $this->set('_serialize', ['device']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $device = $this->Devices->newEntity();
        if ($this->request->is('post')) {
            $device = $this->Devices->patchEntity($device, $this->request->data);
            if ($this->Devices->save($device)) {
                $this->Flash->success(__('The device has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The device could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('device'));
        $this->set('_serialize', ['device']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Device id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $device = $this->Devices->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $device = $this->Devices->patchEntity($device, $this->request->data);
            if ($this->Devices->save($device)) {
                $this->Flash->success(__('The device has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The device could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('device'));
        $this->set('_serialize', ['device']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Device id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $device = $this->Devices->get($id);
        if ($this->Devices->delete($device)) {
            $this->Flash->success(__('The device has been deleted.'));
        } else {
            $this->Flash->error(__('The device could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Monitoring screen.
     *
     * @return void
     */
    public function monitoring()
    {
        $this->request->allowMethod(['get']);
        $list = $this->Devices->find()->select(['id', 'name'])->combine('id', 'name');
        $this->set('devices', $list);
    }
}
