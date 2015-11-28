<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Sidebar->action(__('Edit Device'), ['action' => 'edit', $device->id], 'pencil') ?>
            <?= $this->Sidebar->postAction(__('Delete Device'), ['action' => 'delete', $device->id], 'times', ['confirm' => __('Are you sure you want to delete {0}?', $device->name)]) ?>
            <?= $this->Sidebar->action(__('List Devices'), ['action' => 'index'], 'list') ?>
            <?= $this->Sidebar->action(__('New Device'), ['action' => 'add'], 'plus') ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <h3><?= __('Device Details'); ?>
    <h4><?= h($device->name) ?> &nbsp;&nbsp;&nbsp;&nbsp; <small>(<?= h($device->ip_address) ?>)</small></h4>
    <p><?= $device->description ? h($device->description) : __('No description') ?></p>
    <p><?= __('Last Updated') ?>: <?= $device->last_updated ? h($device->last_updated) : __('Never') ?></p>

    <h4><?= __('System Description (SNMP)') ?></h4>
    <p><?= $data->description ?></p>

    <h4><?= __('Installed Softwares') ?></h4>
    <?= $this->Paginator->counter(['format' => __('{{count}} softwares found')]); ?>
    <table class="table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('name', __('Name')) ?></th>
<!--                <th>< ?= $this->Paginator->sort('install_date', __('Install Date')) ?></th>
                <th>< ?= $this->Paginator->sort('type', __('Type')) ?></th>-->
            </tr>
        </thead>
        <tbody>
            <?php if (empty($softwares)): ?>
            <tr>
                <td colspan="3"><?= __('No data available') ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach ($softwares as $software): ?>
            <tr>
                <td><?= h($software->name) ?></td>
<!--                <td>< ?= h($software->install_date) ?></td>
                <td>< ?= h($software->type) ?></td>-->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Paginator->numbers(['prev' => '< ' . __('previous'), 'next' => __('next') . ' >']); ?>
</main>
