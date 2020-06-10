
function payWithPaystack(payment, callbackfunction, close){
    var handler = PaystackPop.setup({
        key: 'pk_test_1f3f470c45933c34b76b13e1a924fe53486870b6',
        email: payment.email,
        amount: 100 * payment.amount,
        ref: payment.transaction_no,
        firstname: payment.firstname,
        lastname: payment.lastname,
        metadata: {
            custom_fields: [
                {
                    display_name: 'Advance',
                    variable_name: "",
                    value: payment.description
                },
                {
                    display_name: 'Invoice No',
                    variable_name: "invoice",
                    value: payment.transaction_no
                }
            ]
        },
        callback: callbackfunction,
        onClose: close
    });

    handler.openIframe();

}