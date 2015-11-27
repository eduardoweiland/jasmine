<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DeviceData Entity.
 *
 * @property int $id
 * @property int $device_id
 * @property \App\Model\Entity\Device $device
 * @property \Cake\I18n\Time $updated
 * @property string $description
 * @property int $uptime
 * @property int $total_ram
 * @property int $available_ram
 * @property int $used_ram
 * @property int $total_disk
 * @property int $available_disk
 * @property int $used_disk
 */
class DeviceData extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
