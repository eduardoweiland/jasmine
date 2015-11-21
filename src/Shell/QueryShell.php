<?php

namespace App\Shell;

use App\Model\Entity\Device;
use App\Model\Table\DevicesTable;
use Cake\Console\Shell;
use Cake\I18n\Time;
use SNMP;

/**
 * Query shell command.
 *
 * @property DevicesTable $Devices Devices Model
 */
class QueryShell extends Shell
{
    /**
     * Inicializa o shell, carregando os models necessários.
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Devices');
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
        $snmp = new SNMP(SNMP::VERSION_2C, $device->ip_address, $device->snmp_community);
        $snmp->valueretrieval = SNMP_VALUE_OBJECT | SNMP_VALUE_LIBRARY;
        $snmp->oid_output_format = SNMP_OID_OUTPUT_NUMERIC;
        $snmp->quick_print = true;
        $snmp->enum_print = true;

        $data = $this->queryDeviceData($snmp);
        $softwares = $snmp->walk('HOST-RESOURCES-MIB::hrSWInstalledTable', true);

        debug($data);
        debug($softwares);

        $device->last_updated = Time::now();
        $this->Devices->save($device);
    }

    /**
     * Get scalar information from one device.
     *
     * @param SNMP $snmp SNMP connection with the specified device.
     * @return DeviceData Object with latest information for this device.
     */
    private function queryDeviceData(SNMP $snmp)
    {
        $rawData = $snmp->get([
            'SNMPv2-MIB::sysDescr.0',
            'HOST-RESOURCES-MIB::hrSystemUptime.0',
            'UCD-SNMP-MIB::memTotalReal.0',
            'UCD-SNMP-MIB::memAvailReal.0',
            'UCD-SNMP-MIB::dskTotal.1',
            'UCD-SNMP-MIB::dskAvail.1',
            'UCD-SNMP-MIB::dskUsed.1'
        ]);

        $data = new DeviceData();
        $data->description = $rawData['SNMPv2-MIB::sysDescr.0'];

        return $data;
    }

    /**
     * Get table of softwares installed on device.
     *
     * @param SNMP $snmp SNMP connection with the specified device.
     * @return DeviceSoftware[] Array of objects with table of installed software.
     */
    private function queryDeviceSoftware(SNMP $snmp)
    {
        $rawData = $snmp->walk('HOST-RESOURCES-MIB::hrSWInstalledTable', true);
        $softwares = [];

        foreach ($rawData as $oid => $info) {
            $software = new DeviceSoftware();
            $software->name = $info->name;
            $softwares[] = $software;
        }

        return $softwares;
    }
}
