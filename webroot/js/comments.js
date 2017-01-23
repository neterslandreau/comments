$(function() {
    // toggle add forms on view
    $('[id^="button_"]').on('click', function() {
        $('#form_'+this.id.split('_')[1]).toggle();
    });

    // toggle edit buttons on view
    $('[id^="editbutton_"]').on('click', function() {
        $('#editform_'+this.id.split('_')[1]).toggle();
    });

    // check all for admin index display
    $('#mainCheck').on('change', function() {
       $('.cbox').prop('checked', !!$(this).prop('checked'));
    });

    // $('#process').submit(function(event) {
    //     console.log(this);
    //     event.preventDefault();
    // });
});