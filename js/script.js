// Title:       script.ks
// Application: RentalBuddy
// Purpose:     Scripts for utility functions
// Author:      G. Blandford,  Group 5, INFO-5139-01-21W
// Date:        March 14th, 2021 (March 14th, 2021)

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
