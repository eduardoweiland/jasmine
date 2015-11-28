<?php
    $this->Html->css('bootstrap-multiselect.css', ['block' => true]);

    $this->Html->script([
        'vendor/bootstrap-multiselect.js',
        'vendor/knockout-3.4.0.js',
        'vendor/jquery.flot.min.js',
        'vendor/jquery.flot.resize.min.js',
        'vendor/jquery.flot.time.min.js',
        'vendor/jquery.flot.tooltip.min.js',
        'vendor/moment-with-locales.min.js',
        'vendor/moment-duration-format.js',
        'knockout-flotchart.js',
        'monitoring.js'
    ], ['block' => true]);
?>

<main id="monitoring" class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body row">
            <div class="col-sm-3">
                <div class="form-group">
                    <?= $this->Form->label(__('Refresh interval:')) ?>
                    <div class="input-group">
                        <input type="number" name="refresh" class="form-control" min="1" data-bind="value: updateInterval" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" data-bind="click: refresh"><i class="fa fa-refresh"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <?= $this->Form->label(__('Data Interval:')) ?>
                <?= $this->Form->select('interval', $intervals, ['data-bind' => 'value: dataInterval']) ?>
            </div>
            <div class="col-sm-6">
                <?= $this->Form->label(__('Devices:')) ?>
                <?= $this->Form->select('devices', $devices, ['multiple' => true]) ?>
            </div>
        </div>
    </div>
    <div data-bind="foreach: monitoredDevices">
        <div class="device">
            <h3 data-bind="text: name"></h3>
            <h5><?= __('Uptime:') ?> <span data-bind="text: uptime"></span></h5>
            <div class="plot" data-bind="flotChart: ramData, flotOptions: $parent.PLOT_OPTIONS"></div>
            <div class="plot" data-bind="flotChart: diskData, flotOptions: $parent.PLOT_OPTIONS"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</main>
