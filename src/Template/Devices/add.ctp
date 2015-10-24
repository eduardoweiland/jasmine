<aside class="col-md-3">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= __('Actions') ?></h4></div>
        <nav class="list-group">
            <?= $this->Html->link(__('List Devices'), ['action' => 'index'], ['class' => 'list-group-item']) ?>
        </nav>
    </div>
</aside>
<main class="col-md-9">
    <?= $this->Form->create($device) ?>
    <fieldset>
        <legend><?= __('Add Device') ?></legend>
        <?php
            echo $this->Form->input('ip_address', ['label' => __('IP Address')]);
            echo $this->Form->input('update_interval', ['label' => __('Update Interval (in minutes)'), 'min' => 5]);
            echo $this->Form->input('snmp_community', ['label' => __('SNMP Community')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</main>
