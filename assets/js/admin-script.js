jQuery('.edit_invoice_details').hide();

function edit_invoice_details(order_id){
    var billing_invoice_company =  jQuery("#billing_invoice_company").val();
    var billing_invoice_mol = jQuery("#billing_invoice_mol").val();
    var billing_invoice_eik = jQuery("#billing_invoice_eik").val();
    var billing_invoice_vatnum = jQuery("#billing_invoice_vatnum").val();
    var billing_invoice_town = jQuery("#billing_invoice_town").val();
    var billing_invoice_address = jQuery("#billing_invoice_address").val();

    jQuery.ajax({

            url: ajaxurl,
            dataType: "json",
            
            data: {
                action: 'cif_handle_ajax',
                action2: 'edit_invoice_details',
                billing_invoice_company: billing_invoice_company,
                billing_invoice_mol: billing_invoice_mol,
                billing_invoice_eik: billing_invoice_eik,
                billing_invoice_vatnum: billing_invoice_vatnum,
                billing_invoice_town: billing_invoice_town,
                billing_invoice_address: billing_invoice_address,
                order_id: order_id,
            },

            success: function(data){
                location.reload();
            },

    });

}

jQuery("a.edit_invoice").click(function(e){
    e.preventDefault();
    jQuery('.invoice_details').hide();
    jQuery('.edit_invoice_details').show();
});