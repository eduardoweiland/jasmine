<?php
use Migrations\AbstractMigration;

class CreateTableDevices extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('devices');
        $table
            ->addColumn('update_interval', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('last_updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('ip_address', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('snmp_community', 'string', [
                'default' => null,
                'limit' => 120,
                'null' => true,
            ])
            ->create();

    }

    public function down()
    {
        $this->dropTable('devices');
    }
}
