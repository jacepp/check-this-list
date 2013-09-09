jQuery( function( $ ) {
  var check = $('#disabled').text();

  if(check != 'Check this list is disabled') {
    $( '#publish' ).hide();

    $( 'input[type="checkbox"].chkbx' ).change( function(){
      var a = $( 'input[type="checkbox"].chkbx' );

      if( a.length == a.filter( ':checked' ).length ){
        $( '#publish' ).fadeIn().show();
      } else {
        $( '#publish' ).fadeOut().hide();
      }
    });
  }
    
  $( '#add' ).click( function(e) {
    e.preventDefault;

    var counter;
    var form_inputs = $( 'form' ).find( 'input[type="text"]' );
    var input = $( '#ctl-create-list-0' );

    $.each( form_inputs, function( i, item ) {
      counter = i + 1;
    });

    var new_input = '<input id="ctl-create-list-'+ counter +'" size="50" name="ctl_create_list['+ counter +']" type="text" value="" required />';
    $( new_input ).css( 'display', 'block' ).appendTo( $( input ).parent() );
    
    return false; 
  });

  $( '#minus' ).click( function(e) {
    e.preventDefault;
    
    var counter;
    var form_inputs = $( 'form' ).find( 'input[type="text"]' );

    $.each( form_inputs, function( i, item ) {
      counter = i;
    });

    if( counter !== 0 ) {
      $( '#ctl-create-list-'+ counter ).remove();
    }

    return false; 
  });

  $( '#ctl-disable-button' ).change( function() {
    var form_inputs = $( 'form' ).find( 'input[type="text"]' );

    if( $( '#ctl-disable-button' ).is( ':checked' ) ) {
      $.each( form_inputs, function( i, item ) {
        $( this ).removeAttr( 'name required' ).fadeOut().hide();
      });

      $( '#buttons' ).fadeOut().hide();
    } else {
      $.each( form_inputs, function( i, item ) {
        $( this ).attr( { name: 'ctl-create-list-'+ i, required: true } ).fadeIn().show();
      });

      if( form_inputs.length > 0 ) {
        $( '#buttons' ).fadeIn().show();
      }
    }
  }); 

  if( $( '#ctl-disable-button' ).is( ':checked' ) ) {
    $( '#buttons' ).fadeOut().hide();
  }
});