<?php

namespace App\Shell\Task;

use Cake\Console\Shell;
use SNMP;

/**
 * SnmpTask.
 *
 * @method mixed get(mixed $object_id, bool $preserve_keys = false) SNMP::get
 * @method int getErrno() SNMP::getErrno
 * @method string getError() SNMP::getError
 * @method mixed getnext(mixed $object_id) SNMP::getnext
 * @method bool set(mixed $object_id, mixed $type, mixed $value) SNMP::set
 * @method bool setSecurity(string $sec_level,
 *                          string $auth_protocol = '',
 *                          string $auth_passphrase = '',
 *                          string $priv_protocol = '',
 *                          string $priv_passphrase = '',
 *                          string $contextName = '',
 *                          string $contextEngineID = '') SNMP::setSecurity
 * @method array walk(string $object_id,
 *                    bool $suffix_as_key = false,
 *                    int $max_repetitions = null,
 *                    int $non_repeaters = null) SNMP::walk
 */
class SnmpTask extends Shell
{
    /**
     * SNMP object used to communicate with device.
     *
     * @var SNMP
     */
    private $snmp;

    /**
     * Open a SNMP connection with a specified device.
     *
     * @param int $version SNMP protocol version (e.g. SNMP::VERSION_2C).
     * @param string $address Device address, either IP or hostname.
     * @param string $community SNMP community.
     *
     * @see SNMP
     */
    public function open($version, $address, $community)
    {
        $this->snmp = new SNMP($version, $address, $community);
        $this->snmp->valueretrieval = SNMP_VALUE_OBJECT | SNMP_VALUE_LIBRARY;
        $this->snmp->quick_print = true;
        $this->snmp->enum_print = true;
    }

    /**
     * Close connection with device.
     */
    public function close()
    {
        $this->snmp->close();
        $this->snmp = null;
    }

    /**
     * Magic method used for directly calling methods from SNMP class.
     *
     * @param string $name Method name.
     * @param array $arguments Method arguments.
     * @return mixed Method returned value.
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->snmp, $name], $arguments);
    }

    /**
     * Request many objects from the device, and return them as an associative
     * array using the same keys as the input array $oid.
     *
     * Example usage:
     *
     * ``` php
     * $snmpTask->getMany([
     *     'description' => 'SNMPv2-MIB::sysDescr.0',
     *     'total_ram' => 'UCD-SNMP-MIB::memTotalReal.0',
     *     'uptime' => 'HOST-RESOURCES-MIB::hrSystemUptime.0'
     * ]);
     * // Returned value:
     * // Array
     * // (
     * //     [description] => System description as returned by SNMP
     * //     [total_ram] => 3907272
     * //     [uptime] => 10899
     * // )
     * ```
     *
     * @param array $oids
     * @return array Associative array with response values.
     */
    public function getMany(array $oids)
    {
        $response = $this->snmp->get(array_values($oids), true);
        $parsedResponse = [];

        foreach ($oids as $key => $requestedObjectId) {
            $parsedResponse[$key] = $this->parseResponseValue($response[$requestedObjectId]);
        }

        return $parsedResponse;
    }

    /**
     * Request a list of SNMP instances for a specified object ID.
     *
     * Example usage:
     *
     * ``` php
     * $snmpTask->getList('HOST-RESOURCES-MIB::hrSWInstalledName');
     * // Returned value:
     * // Array
     * // (
     * //     [0] => php-5.6.15-1.fc23
     * //     [1] => php-cli-5.6.15-1.fc23
     * //     [2] => php-common-5.6.15-1.fc23
     * //     [...] => ...
     * // )
     * ```
     *
     * @param string $oid Parent object ID
     * @return mixed[]
     */
    public function getList($oid)
    {
        $response = $this->snmp->walk($oid);
        $list = [];

        foreach ($response as $value) {
            $list[] = $this->parseResponseValue($value);
        }

        return $list;
    }

    /**
     * Parse SNMP response value for an object.
     *
     * This method identifies the object type and convert the returned value to
     * the matching PHP data type.
     *
     * @param \stdClass $response
     * @return mixed Parsed response value.
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    protected function parseResponseValue(\stdClass $response)
    {
        $value = null;

        switch ($response->type) {
            case SNMP_INTEGER:
            case SNMP_COUNTER:
            case SNMP_UINTEGER:
                $value = intval($response->value);
                break;
            case SNMP_OCTET_STR:
            case SNMP_OBJECT_ID:
            case SNMP_BIT_STR:
                $value = (string)trim($response->value, '"');
                break;
            case SNMP_TIMETICKS:
                list($days, $hours, $minutes, $seconds) = sscanf($response->value, '%d:%d:%d:%d.%d');
                $value = $seconds + ($minutes * 60) + ($hours * 3600) + ($days * 86400);
                break;
            case SNMP_NULL:
                throw new \RuntimeException('Not implemented. Format: ' . $response->value);
            case SNMP_IPADDRESS:
                throw new \RuntimeException('Not implemented. Format: ' . $response->value);
            case SNMP_OPAQUE:
                throw new \RuntimeException('Not implemented. Format: ' . $response->value);
            default:
                throw new \UnexpectedValueException('Unknown SNMP type: ' . $response->type);
        }

        return $value;
    }
}
