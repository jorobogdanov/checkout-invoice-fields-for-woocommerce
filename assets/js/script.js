
jQuery(document).ready(function( $ ) {
    $('input[type=checkbox][name=billing_is_invoice]').on('change', function () {
        if($('input[type=checkbox][name=billing_is_invoice]').is(':checked')){
            $('.invoice').show(500);
        } else {
            $('.invoice').hide(500);
        }
    }).trigger('change');
});