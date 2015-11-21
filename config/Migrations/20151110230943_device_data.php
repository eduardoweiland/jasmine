<?php
use Migrations\AbstractMigration;

class DeviceData extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('device_data');
        $table
            ->addColumn('device_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('uptime', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('total_ram', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('available_ram', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('used_ram', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('total_disk', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('available_disk', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('used_disk', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'device_id',
                ]
            )
            ->create();

        $table = $this->table('device_software');
        $table
            ->addColumn('device_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
            ])
            ->addIndex(
                [
                    'device_id',
                ]
            )
            ->create();

        $this->table('device_data')
            ->addForeignKey(
                'device_id',
                'devices',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('device_software')
            ->addForeignKey(
                'device_id',
                'devices',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

    }

    public function down()
    {
        $this->table('device_data')
            ->dropForeignKey(
                'device_id'
            );

        $this->table('device_software')
            ->dropForeignKey(
                'device_id'
            );

        $this->dropTable('device_data');
        $this->dropTable('device_software');
        $this->dropTable('devices');
    }
}
