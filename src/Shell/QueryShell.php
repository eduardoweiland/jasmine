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

    private function updateDevice(Device $device)
    {
        $snmp = new SNMP(SNMP::VERSION_2C, $device->ip_address, $device->snmp_community);
        $snmp->valueretrieval = SNMP_VALUE_OBJECT | SNMP_VALUE_LIBRARY;
        $snmp->oid_output_format = SNMP_OID_OUTPUT_NUMERIC;
        $snmp->quick_print = true;
        $snmp->enum_print = true;

        $data = $snmp->get([
            'SNMPv2-MIB::sysDescr.0',
            'HOST-RESOURCES-MIB::hrSystemUptime.0',
            'UCD-SNMP-MIB::memTotalReal.0',
            'UCD-SNMP-MIB::memAvailReal.0',
            'UCD-SNMP-MIB::dskTotal.1',
            'UCD-SNMP-MIB::dskAvail.1',
            'UCD-SNMP-MIB::dskUsed.1'
        ]);

        $softwares = $snmp->walk('HOST-RESOURCES-MIB::hrSWInstalledTable', true);

        debug($data);
        debug($softwares);

        $device->last_updated = Time::now();
        $this->Devices->save($device);
    }
}
