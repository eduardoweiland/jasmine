(function(ko, $) {

    ko.bindingHandlers.multiselect = {
        init: function(element, valueAcessor) {
            var options = ko.unwrap(valueAcessor());
            $(element).multiselect(options);
        },
        update: function(element) {
            $(element).multiselect('refresh');
        }
    };

})(window.ko, window.jQuery);