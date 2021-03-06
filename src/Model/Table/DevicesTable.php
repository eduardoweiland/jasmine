<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Devices Model
 *
 */
class DevicesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('devices');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('DeviceData', [
            'propertyName' => 'device_data',
            'dependent' => true
        ]);

        $this->hasMany('DeviceSoftware', [
            'propertyName' => 'device_softwares',
            'dependent' => true
        ]);
    }

    /**
     * Find a list of devices that need to be updated via SNMP.
     *
     * This list is based on each device update interval and last updated time.
     *
     * @return Cake\ORM\Query
     */
    public function findUpdatePending()
    {
        $query = $this->find();
        $condition = $query->newExpr('last_updated IS NULL OR DATE_ADD(last_updated, INTERVAL update_interval MINUTE) <= NOW()');
        $query->where($condition);
        return $query->select();
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->add('update_interval', 'valid', ['rule' => 'numeric'])
            ->requirePresence('update_interval', 'create')
            ->notEmpty('update_interval');

        $validator
            ->add('update_interval', 'valid', ['rule' => 'numeric'])
            ->requirePresence('update_interval', 'create')
            ->notEmpty('update_interval');

        $validator
            ->add('last_updated', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('last_updated');

        $validator
            ->requirePresence('ip_address', 'create')
            ->notEmpty('ip_address');

        $validator
            ->allowEmpty('snmp_community');

        $validator
            ->allowEmpty('description');

        return $validator;
    }
}
