<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Html->link(__('New Device'), ['action' => 'add'], ['class' => 'list-group-item']) ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <h3><?= __('Devices') ?></h3>
    <table class="table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('name', __('Name')) ?></th>
                <th><?= $this->Paginator->sort('ip_address', __('IP Address')) ?></th>
                <th><?= $this->Paginator->sort('update_interval', __('Update Interval')) ?></th>
                <th><?= $this->Paginator->sort('description', __('Description')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $device): ?>
            <tr>
                <td><?= h($device->name) ?></td>
                <td><?= h($device->ip_address) ?></td>
                <td><?= __('{0} minutes', $this->Number->format($device->update_interval)) ?></td>
                <td><?= h($device->description) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $device->id], ['class' => 'btn btn-primary btn-xs']) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $device->id], ['class' => 'btn btn-warning btn-xs']) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete {0}?', $device->name), 'class' => 'btn btn-danger btn-xs']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</main>
