<?php

namespace App\Shell;

use App\Model\Entity\Device;
use App\Model\Entity\DeviceData;
use App\Model\Entity\DeviceSoftware;
use Cake\Console\Shell;
use Cake\I18n\Time;
use SNMP;

/**
 * Query shell command.
 *
 * @property App\Model\Table\DevicesTable $Devices Devices Model
 * @property App\Model\Table\DeviceSoftwareTable $DeviceSoftware Devices Model
 * @property Task\SnmpTask $Snmp Snmp Task
 */
class QueryShell extends Shell
{
    /**
     * Lista de tasks para serem inicializadas.
     *
     * @var array
     */
    public $tasks = ['Snmp'];

    /**
     * Inicializa o shell, carregando os models necessários.
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Devices');
        $this->loadModel('DeviceSoftware');
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $devices = $this->Devices->findUpdatePending();

        foreach ($devices as $device) {
            $this->updateDevice($device);
        }

        return true;
    }

    /**
     * Updates a device by issuing SNMP GET requests for all informations
     * that this software handles.
     *
     * @param Device $device Device to be updated.
     */
    private function updateDevice(Device $device)
    {
        $this->Snmp->open(SNMP::VERSION_2C, $device->ip_address, $device->snmp_community);

        // Remove old list of softwares of this device and get a new one
        $this->DeviceSoftware->deleteAll(['device_id' => $device->id]);
        $device->device_softwares = $this->queryDeviceSoftware();

        // Get other informations from the device
        $device->appendDeviceData($this->queryDeviceData());

        // Save them all
        $device->last_updated = Time::now();
        $this->Devices->save($device);

        $this->Snmp->close();
    }

    /**
     * Get scalar information from one device.
     *
     * @return DeviceData Object with latest information for the device.
     */
    private function queryDeviceData()
    {
        $data = $this->Snmp->getMany([
            'description'    => 'SNMPv2-MIB::sysDescr.0',
            'uptime'         => 'HOST-RESOURCES-MIB::hrSystemUptime.0',
            'total_ram'      => 'UCD-SNMP-MIB::memTotalReal.0',
            'available_ram'  => 'UCD-SNMP-MIB::memAvailReal.0',
            'total_disk'     => 'UCD-SNMP-MIB::dskTotal.1',
            'available_disk' => 'UCD-SNMP-MIB::dskAvail.1',
            'used_disk'      => 'UCD-SNMP-MIB::dskUsed.1'
        ]);

        $deviceData = new DeviceData($data);
        $deviceData->used_ram = $deviceData->total_ram - $deviceData->available_ram;

        return $deviceData;
    }

    /**
     * Get table of softwares installed on device.
     *
     * @return DeviceSoftware[] Array of objects with table of installed software.
     */
    private function queryDeviceSoftware()
    {
        $list = $this->Snmp->getList('HOST-RESOURCES-MIB::hrSWInstalledName');
        $softwares = [];

        foreach ($list as $name) {
            $software = new DeviceSoftware();
            $software->name = $name;
            $softwares[] = $software;
        }

        return $softwares;
    }
}
