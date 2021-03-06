// Title:       script.ks
// Application: RentalBuddy
// Purpose:     Scripts for utility functions
// Author:      G. Blandford,  Group 5, INFO-5139-01-21W
// Date:        March 14th, 2021 (March 14th, 2021)

// 20210413 GPB Added showHideCC for credit card details

function showMessages(msgs) {

    if (msgs === undefined) {
        $('#div-messages').html('');
        return;
    }

    let html = '<div class="alert alert-success alert-dismissible fade show" role="alert">';

    html += msgs;
    html += '<button type="button" class="btn close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    $('#div-messages').html(html);

    setTimeout(function () { showMessages(); }, 3000);
}

function showErrors(errs) {

    if (errs === undefined) {
        $('#div-errors').html('');
        return;
    }

    let html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';

    html += errs;
    html += '<button type="button" class="btn close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    $('#div-errors').html(html);

    setTimeout(function () { showErrors(); }, 3000);
}

function showHideCC() {

    e = document.getElementById('payment-type-code');
    if (e != undefined) {
        paymentCode = e.value;        
        if (paymentCode == 'debitcredit') {
            document.getElementById('fieldset-card').style.display='inherit';
        } else {
            document.getElementById('fieldset-card').style.display='none';
        }
    }
}

function formatCC() {

    cardNumber = $gel('card-number').value;

    let fCC = cardNumber.replace(/\D/g,'');
    if (fCC == "") {
        return fCC;
    }

    fCC = fCC.substr( 0, 4) + "-" + fCC.substr(4, 4) + "-" + fCC.substr(8, 4) + "-" + fCC.substr(12, 4);
    $gel('card-number').value = fCC;
}

function showPaymentDiscount() {

    // Get the Discount value
    let e = $gel('discount-coupon-code');
    let discountRate = e.options[e.selectedIndex].getAttribute('data-discount');

    if (discountRate == 0) {
        return;
    } 

    // Apply it to the rent
    let due = $gel('payment-due').value;
    let discountVal = due * (discountRate / 100);
    let amount = (due - discountVal);

    // Update UI
    $gel('discount').value = discountVal.toFixed(2);
    $gel('payment-amount').value = amount.toFixed(2);

}

// Helper
function $gel(e) {
    return document.getElementById(e);
}