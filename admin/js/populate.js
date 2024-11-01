jQuery(function($){
 
 
  $( 'body' ).on( 'click', 'input[name="bulk_edit"]', function(e) {
 
		$( this ).after('<span class="spinner is-active"></span>');
 
 
		 
		var bulk_edit_row = $( 'tr#bulk-edit' ),
		    post_ids = new Array()
		    price = bulk_edit_row.find( 'select[name="tu_language_field_postBulk[]"]' ).val(),
		 
		bulk_edit_row.find( '#bulk-titles .button-link' ).each( function() {
			post_ids.push( $( this ).attr( 'id' ).replace( '_', '' ) );
		});
 
 var ajaxs = $.ajax({
    url: ajaxurl, // WordPress has already defined the AJAX url for us (at least in admin area)
    type: 'POST',
    dataType: 'json',
    async: false,
        cache: false,
        timeout: 30000,
    data: {
      action: 'bulk_edit', // wp_ajax action hook
      post_ids: post_ids, // array of post IDs
      price: price, // new 
 
    
    	nonce: $('#misha_nonce').val() // I take the nonce from hidden #misha_nonce field
    },
    success: function(results) {  
     
      
          }
    
  });

  setTimeout(function() {
   return true;
}, 900);
 
});


if(typeof inlineEditPost!=='undefined'){
var wp_inline_edit_function = inlineEditPost.edit;

// we overwrite the it with our own
inlineEditPost.edit = function( post_id ) {

  // let's merge arguments of the original function
  wp_inline_edit_function.apply( this, arguments );

  // get the post ID from the argument
  var id = 0;
  if ( typeof( post_id ) == 'object' ) { // if it is object, get the ID number
    id = parseInt( this.getId( post_id ) );
  }
  
  //if post id exists
  if ( id > 0 ) {
  


    // add rows to variables
    var specific_post_edit_row = $( '#edit-' + id );
        specific_post_row = $( '#post-' + id );
        product_price = $( '.column-language', specific_post_row ).find(".idk").data('stuff'); //  remove $ sign
 
        $( ':input[name="tu_language_field_post[]"]', specific_post_edit_row ).val(product_price);
    
  
    $('.select2-container').remove();
    var selector = $(".TUlanguagesAddEdit");
   
  
    selector.select2({
        placeholder: "Click here to select language",
        allowClear: false,
        
    });
    $(".TUlanguagesAddEdit").each(function () {
      var self = $(this);
      var select2Instance = self.data("select2");
      var resetOptions = select2Instance.options.options;
      self.select2("destroy")
          .select2(resetOptions);
  });
   
  
  }
}
}
$( 'body' ).on( 'click', '#doaction', function(e) {
$('.select2-container').remove();
var selector = $(".TUlanguagesAddEditBulk");

selector.select2({
    placeholder: "Click here to select language",
    allowClear: false,
    
});
$(".TUlanguagesAddEditBulk").each(function () {
  var self = $(this);
  var select2Instance = self.data("select2");
  var resetOptions = select2Instance.options.options;
  self.select2("destroy")
      .select2(resetOptions);
});
});


});