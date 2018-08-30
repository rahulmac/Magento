define([
    'uiElement',
    'jquery',
    'jquery/ui'
], function(Component,$) {
    'use strict';
    $( document ).ready(function() {
        $('#success-message').hide();
        $('#failure-message').hide();
        $('#savesettings').on('click',function(){

            if ($('input[name="apikey"]').val() == '') {
                $('#error-message').text('Api key can not be empty!');
                $('#failure-message').show();
                $('#failure-message').delay(4000).fadeOut();
                return false;
            }
            var current_reference = $(this);
            $.ajax({
                url: $('#vatdiscountform').attr('action'),
                showLoader: true,
                data:  $('#vatdiscountform').serialize(),
                type: "post",
                cache: false,
                success: function (data) {

                    if (data.success == true) {
                        $('#success-message').show();
                        $('#success-message').delay(4000).fadeOut();
                        $('#vatdiscountform').attr('action', $('#action_url').val());
                    } else if (data.success == false) {
                        $('#error-message').text(data.message);
                        $('#failure-message').show();
                        $('#failure-message').delay(4000).fadeOut();
                    }
                }
            });

        });
    });
    return Component.extend();
});