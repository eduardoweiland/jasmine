jQuery(function($) {
    $('[name="devices[]"]').multiselect({
        maxHeight: 200,
        buttonWidth: '100%'
    });

    $('#btnRefresh').click(function() {
        // TODO
        window.location.reload();
    });
});
