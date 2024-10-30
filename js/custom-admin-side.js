jQuery(function($) {
    
    $('.jp-disconnect').click(function(){
        $( "#disconnect-dialog-confirm" ).dialog('open');
    });

    $('#disconnect-dialog-confirm').dialog({
          resizable: false,
          // height: 200,
          // width: 400,
          autoOpen: false,
          dialogClass: "jp-disconnect-modal",
          modal: true,
          draggable: false,
          buttons: {
            Yes: function() {
                $( this ).dialog( "close" );
                $('.disconnect-form').submit();
            },
            No: function() {
                 $( this ).dialog( "close" );
            }
        }
    });

});