<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Html->link(__('Edit Device'), ['action' => 'edit', $device->id], ['class' => 'list-group-item']) ?>
            <?= $this->Form->postLink(__('Delete Device'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete {0}?', $device->name), 'class' => 'list-group-item']) ?>
            <?= $this->Html->link(__('List Devices'), ['action' => 'index'], ['class' => 'list-group-item']) ?>
            <?= $this->Html->link(__('New Device'), ['action' => 'add'], ['class' => 'list-group-item']) ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <h3><?= __('Device Details'); ?>
    <h4><?= h($device->name) ?> &nbsp;&nbsp;&nbsp;&nbsp; <small>(<?= h($device->ip_address) ?>)</small></h4>
    <p><?= $device->description ? h($device->description) : __('No description') ?></p>
    <p><?= __('Last Updated') ?>: <?= $device->last_updated ? h($device->last_updated) : __('Never') ?></p>

    <h4><?= __('Installed Software') ?></h4>
    <table class="table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('name', __('Name')) ?></th>
                <th><?= $this->Paginator->sort('install_date', __('Install Date')) ?></th>
                <th><?= $this->Paginator->sort('type', __('Type')) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($device->softwares)): ?>
            <tr>
                <td colspan="3"><?= __('No data available') ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach ($device->softwares as $software): ?>
            <tr>
                <td><?= h($software->name) ?></td>
                <td><?= h($software->install_date) ?></td>
                <td><?= h($software->type) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
