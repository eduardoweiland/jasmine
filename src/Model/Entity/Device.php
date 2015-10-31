<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Device Entity.
 *
 * @property int $id
 * @property int $update_interval
 * @property \Cake\I18n\Time $last_updated
 * @property string $ip_address
 * @property string $snmp_community
 */
class Device extends Entity
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
