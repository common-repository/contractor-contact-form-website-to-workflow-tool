jQuery(function($) {
    
    $( "#sortable_fields" ).sortable({
        placeholder: "ui-state-highlight"
    });

    // show/hide required field 
    $('.field-hide-setting .field-visiblity-checkbox input').change(function() {
        if(this.checked) {
           $(this).parents('.ui-state-default').find('.field-required-setting').find('input[type=checkbox]').attr('disabled', 'disabled');
        }
        if(!this.checked) {
            $(this).parents('.ui-state-default').find('.field-required-setting').find('input[type=checkbox]').prop('disabled', false);
        }
    });

    $('.form-settings-submit #submit').click(function() {
        $('.form-settings-submit .settings-loader').css('display', 'inline-block');
    })
})