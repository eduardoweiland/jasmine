<?php
namespace App\Model\Table;

use App\Model\Entity\DeviceData;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DeviceData Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Devices
 */
class DeviceDataTable extends Table
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

        $this->table('device_data');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Devices', [
            'foreignKey' => 'device_id',
            'joinType' => 'INNER'
        ]);
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
            ->allowEmpty('description');

        $validator
            ->add('uptime', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('uptime');

        $validator
            ->add('total_ram', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('total_ram');

        $validator
            ->add('available_ram', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('available_ram');

        $validator
            ->add('used_ram', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('used_ram');

        $validator
            ->add('total_disk', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('total_disk');

        $validator
            ->add('available_disk', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('available_disk');

        $validator
            ->add('used_disk', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('used_disk');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['device_id'], 'Devices'));
        return $rules;
    }
}
