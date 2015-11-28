(function($, ko) {
    'use strict';

    var Monitoring = function() {
        this.init.apply(this, arguments);
    };

    Monitoring.prototype = {
        init: function() {
            this.updateTimer      = null;
            this.updateInterval   = ko.observable(5);
            this.dataInterval     = ko.observable(15);
            this.selectedDevices  = ko.observableArray([1,2,3]);
            this.monitoredDevices = ko.observableArray([]);

            this.updateInterval.subscribe(this.refresh, this);
        },

        /**
         * Configurações para os gráficos utilizados no monitoramento.
         *
         * @readonly
         */
        PLOT_OPTIONS: {
            xaxis: {
                mode: 'time',
                timezone: 'browser'
            },
            yaxis: {
                tickFormatter: function(v) {
                    return v + 'MB';
                }
            },
            lines: {
                show: true,
                fill: true
            },
            points: {
                show: true
            },
            grid: {
                hoverable: true
            },
            tooltip: {
                show: true,
                content: function(label, x, y) {
                    return label + ' ' + Math.ceil(y) + 'MB';
                }
            }
        },

        refresh: function() {
            var self = this;

            self.getData(function(devices) {
                self.monitoredDevices([]);

                for (var i = 0, l = devices.length; i < l; ++i) {
                    var obj = {};
                    obj.name = devices[i].name;

                    if (devices[i].uptime) {
                        obj.uptime = moment.duration(devices[i].uptime, 'seconds')
                                .format('Y [anos] M [meses] d[d] h[h] m[min] s[s]');
                    }
                    else {
                        obj.uptime = 'Desconhecido';
                    }

                    obj.ramData  = self.createPlotData(devices[i].data, 'ram');
                    obj.diskData = self.createPlotData(devices[i].data, 'disk');

                    self.monitoredDevices.push(obj);
                }

                self.scheduleNextRefresh();
            });
        },

        createPlotData: function(data, type) {
            var dataset = [
                {label: 'Total',      property: 'total',     data: []},
                {label: 'Disponível', property: 'available', data: []},
                {label: 'Em uso',     property: 'used',      data: []}
            ];

            for (var plotLine = 0; plotLine < dataset.length; ++plotLine) {
                var propertyName = dataset[plotLine].property + '_' + type;

                for (var historyIndex = 0; historyIndex < data.length; ++historyIndex) {
                    var entry = data[historyIndex];

                    dataset[plotLine].data.push([
                        new Date(entry.updated).getTime(),
                        entry[propertyName] / 1024
                    ]);
                }
            }

            return dataset;
        },

        /**
         * Agenda a próxima atualização automática do monitoramento, a partir
         * da configuração do período de atualização automática.
         *
         * @returns {undefined}
         */
        scheduleNextRefresh: function() {
            if (this.updateTimer) {
                window.clearTimeout(this.updateTimer);
            }

            this.updateTimer = window.setTimeout(
                    this.refresh.bind(this),
                    this.updateInterval() * 60 * 1000
            );
        },

        /**
         * Atualiza os dados de monitoramento utilizando uma requisição AJAX.
         *
         * Os dados retornados pelo servidor incluem todo o histórico dos
         * dispositivos selecionados dentro do período escolhido.
         *
         * @param {function} callback Função para receber os dados.
         * @returns {undefined}
         */
        getData: function(callback) {
            var params = {
                devices: this.selectedDevices(),
                interval: this.dataInterval()
            };

            $.getJSON('/monitoring-data', params, function(data) {
                if (data && data.devices) {
                    callback(data.devices);
                }
            });
        }
    };

    $(function($) {
        // Lista de dispositivos para selecionar
        $('[name="devices[]"]').multiselect({
            maxHeight: 200,
            buttonWidth: '100%'
        });

        var monitoring = new Monitoring();
        ko.applyBindings(monitoring, document.getElementById('monitoring'));

        // Atualização inicial dos dados
        monitoring.refresh();
    });
})(window.jQuery, window.ko);
