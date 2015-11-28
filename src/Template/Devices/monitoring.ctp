<?php
    $this->Html->css('bootstrap-multiselect.css', ['block' => true]);

    $this->Html->script([
        'vendor/bootstrap-multiselect.js',
        'vendor/jquery.flot.min.js',
        'vendor/jquery.flot.resize.min.js',
        'vendor/jquery.flot.time.min.js',
        'monitoring.js'
    ], ['block' => true]);
?>

<main class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body row form-inline">
            <div class="col-sm-6">
                <?= $this->Form->input('refresh', ['type' => 'number', 'min' => 1, 'value' => 5, 'label' => __('Refresh interval: ')]) ?>
                <button class="btn btn-default" id="btnRefresh">
                    <?= $this->Html->icon('refresh') ?>
                    <?= __('Refresh now') ?>
                </button>
            </div>
            <div class="col-sm-6">
                <?= $this->Form->select('devices', $devices, ['multiple' => true]) ?>
            </div>
        </div>
    </div>
</main>
