(function(ko, $) {

    ko.bindingHandlers.flotChart = {
        init: function(element, valueAcessor, allBindings) {
            var options = allBindings.get('flotOptions') || {};
            var data = ko.unwrap(valueAcessor());
            $(element).plot(data, options);
        },
        update: function(element, valueAcessor) {
            var plot = $(element).data('plot');
            plot.setData(ko.unwrap(valueAcessor()));
        }
    };

})(window.ko, window.jQuery);