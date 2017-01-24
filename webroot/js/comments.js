$(function() {
    // toggle add forms on view
    $('[id^="button_"]').on('click', function() {
        var bodyId = this.id.split('_')[1];
        $('#formbody_'+bodyId).html('');
        $('#form_'+this.id.split('_')[1]).toggle();
    });

    // toggle edit forms on view
    $('[id^="editbutton_"]').on('click', function() {
        $('#editform_'+this.id.split('_')[1]).toggle();
    });

    $('[id^="quotebutton_"]').on('click', function() {
        var bodyId = (this.id.split('_')[1]);
        var txt = '[quote]'+$('span#body_'+bodyId).html()+'[end quote]';
        $('#formbody_'+bodyId).html(txt);
        $('#form_'+this.id.split('_')[1]).toggle();
    })

    // check all for admin index display
    $('#mainCheck').on('change', function() {
       $('.cbox').prop('checked', !!$(this).prop('checked'));
    });

    // $('#process').submit(function(event) {
    //     console.log(this);
    //     event.preventDefault();
    // });
});