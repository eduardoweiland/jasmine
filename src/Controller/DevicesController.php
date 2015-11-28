<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;

/**
 * Devices Controller
 *
 * @property \App\Model\Table\DevicesTable $Devices
 * @property \App\Model\Table\DeviceSoftwareTable $DeviceSoftware
 * @property \App\Model\Table\DeviceDataTable $DeviceData
 * @property \App\View\Helper\Sidebar $Sidebar
 */
class DevicesController extends AppController
{
    public $helpers = [
        'Sidebar'
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('DeviceSoftware');
        $this->loadModel('DeviceData');
    }

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

        $softwares = $this->paginate(
                $this->DeviceSoftware
                    ->find()
                    ->where(['device_id' => $device->id])
        );

        $data = $this->DeviceData->findLatestData($device);

        $this->set('device', $device);
        $this->set('softwares', $softwares);
        $this->set('data', $data);
        $this->set('_serialize', ['device', 'softwares', 'data']);
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
        $this->set('intervals', [
            '15'  => __('15 minutes'),
            '30'  => __('30 minutes'),
            '60'  => __('1 hour'),
            '120' => __('2 hours')
        ]);
    }

    /**
     * Action to return JSON data used in the monitoring screen.
     *
     * @return void
     */
    public function monitoringData()
    {
        $this->request->allowMethod(['get']);
        $this->RequestHandler->renderAs($this, 'json');

        $listOfDevices = $this->request->query('devices');
        $dataInterval  = $this->request->query('interval');

        if ($listOfDevices === null || $dataInterval === null) {
            throw new BadRequestException('Required parameters were not specified');
        }

        $return = [];

        $devices = $this->Devices
                ->find()
                ->where(['id IN' => $listOfDevices])
                ->toArray();

        $interval = \DateInterval::createFromDateString(intval($dataInterval) . ' minutes');
        $startInterval = (new \DateTime())->sub($interval);

        foreach ($devices as $device) {
            $deviceInfo = [
                'name' => $device->name,
                'uptime' => null,
                'data' => []
            ];

            $recent = $this->DeviceData->findRecentData($device, $startInterval)->toArray();

            if (is_array($recent) && count($recent) >= 1) {
                $latest = $recent[count($recent) - 1];
                $deviceInfo['uptime'] = $latest->uptime;
            }

            $deviceInfo['data'] = $recent;
            $return[] = $deviceInfo;
        }

        $this->set('devices', $return);
        $this->set('_serialize', ['devices']);
    }
}
