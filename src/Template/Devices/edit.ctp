<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Sidebar->postAction(__('Delete Device'), ['action' => 'delete', $device->id], 'times', ['confirm' => __('Are you sure you want to delete {0}?', $device->name)]) ?>
            <?= $this->Sidebar->action(__('List Devices'), ['action' => 'index'], 'list') ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <?= $this->Form->create($device) ?>
    <fieldset>
        <legend><?= __('Edit Device') ?></legend>
        <div class="row">
            <div class="col-sm-6">
                <?= $this->Form->input('name', ['label' => __('Name')]) ?>
            </div>
            <div class="col-sm-6">
                <?= $this->Form->input('ip_address', ['label' => __('IP Address')]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $this->Form->input('update_interval', ['label' => __('Update Interval (in minutes)')]) ?>
            </div>
            <div class="col-sm-6">
                <?= $this->Form->input('snmp_community', ['label' => __('SNMP Community')]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $this->Form->input('description', ['label' => __('Description')]) ?>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'pull-right btn btn-primary btn-lg']) ?>
    <?= $this->Form->end() ?>
</main>
