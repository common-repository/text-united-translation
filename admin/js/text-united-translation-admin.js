(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 */
	 $(function() {
	 if($( "#wpbody-content" ).hasClass( "loginBodyTu" )){
	    var email = $('.loginBodyTu #email').val().length;
	    var choose = $('.loginBodyTu #choose').val().length;
		var name = $('.loginBodyTu #name').val().length;
		var lname = $('.loginBodyTu #lname').val().length;
       if(name>0 && choose>0 && email>0 && lname>0)
	   $(".bgTu").prop("disabled",false).css("background-color","#5CE7C4");
	   else
	   $(".bgTu").prop("disabled",true).css("background-color","#e8e8e8");
	   }
        $('.loginBodyTu input').keyup(function () { 
       var email = $('.loginBodyTu #email').val().length;
	    var choose = $('.loginBodyTu #choose').val().length;
		var name = $('.loginBodyTu #name').val().length;
		var lname = $('.loginBodyTu #lname').val().length;
       if(name>0 && choose>0 && email>0 && lname>0)
	   $(".bgTu").prop("disabled",false).css("background-color","#5CE7C4");
	   else
	   $(".bgTu").prop("disabled",true).css("background-color","#e8e8e8");

		});
		$('#TUlanguages').select2();
		$('#TUlanguagesPost').select2({allowClear: false,placeholder: "Select a new language"});
		$('#TUdefaultLanguage').select2({ theme: "bootstrap4",allowClear: false,placeholder: "Select a source language"});
		$('#TUlanguagesAdd').select2({ theme: "bootstrap4",allowClear: false,  placeholder: "Select a new language",});
		
		$('#TUlanguagesAdd').on('select2:select', function (e) {
			console.log(e.params.data);
			$("#TUlanguagesAddLangCode").val(e.params.data.title);
			$("#TUlanguagesAddDescriptiveName").val(e.params.data.text);
			$("#TUlanguagesAddSystemName").val(e.params.data.element.attributes.system.value);
		  });

		  $('#TUdefaultLanguage').on('select2:select', function (e) {
			console.log(e.params.data);
			$("#TUdefaultLanguageLangCode").val(e.params.data.title);
			$("#TUdefaultLanguageDescriptiveName").val(e.params.data.text);
			$("#TUdefaultLanguageSystemName").val(e.params.data.element.attributes.system.value);
		  });

          $("#saveButtonTU").hide();
		  
		  $( ".deleteList" ).click(function() {
			$(this).parent("li").remove();
			 $("#saveButtonTU").show();
		  });

			$( "#my-list input" ).change(function() {
			   $("#saveButtonTU").show();
			});

		  $( "#my-list select" ).on('change', function() {
			   $("#saveButtonTU").show();
			});


		var el = document.getElementById("my-list");
		if(el){
		var sortable = Sortable.create(el,{ animation: 6,handle: '.sor',ghostClass: 'active',  
		onEnd: function (/**Event*/ evt) {
			$("#saveButtonTU").show();
			$( "#my-list li" ).each(function( index ) {
				$(this).find(".TuOrder").val(index+1);
			  });
		  },});
		}
	  });
	  

	  
	 /*
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );