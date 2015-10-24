<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Html->link(__('Edit Device'), ['action' => 'edit', $device->id], ['class' => 'list-group-item']) ?>
            <?= $this->Form->postLink(__('Delete Device'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete # {0}?', $device->id), 'class' => 'list-group-item']) ?>
            <?= $this->Html->link(__('List Devices'), ['action' => 'index'], ['class' => 'list-group-item']) ?>
            <?= $this->Html->link(__('New Device'), ['action' => 'add'], ['class' => 'list-group-item']) ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <h3><?= h($device->id) ?></h3>
    <table class="table">
        <tr>
            <th><?= __('IP Address') ?></th>
            <td><?= h($device->ip_address) ?></td>
        </tr>
        <tr>
            <th><?= __('SNMP Community') ?></th>
            <td><?= h($device->snmp_community) ?></td>
        </tr>
        <tr>
            <th><?= __('Update Interval') ?></th>
            <td><?= $this->Number->format($device->update_interval) ?> <?= __('minutes') ?></td>
        </tr>
        <tr>
            <th><?= __('Last Updated') ?></th>
            <td><?= h($device->last_updated) ?></tr>
        </tr>
    </table>
</main>
