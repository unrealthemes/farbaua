$(document).ready(function() {
	$('#editTable').on('click', '.editCell', function(){
		var $editDiv = $(this).find('.editDiv');
		if ( $editDiv.is(':visible') ) {
			$('.editTextArea').remove(); 	
			$('.editDiv').show(); 			
			var text = $editDiv.text();
			$textarea = $('<textarea name="editing" class="editTextArea">'+text+'</textarea>');
	        $(this).append($textarea);
	        
	        $editDiv.hide();
	        showCtrls($textarea);
			$textarea.focus();
        }
	});

    $('body').on('click', '#ctrls_holder a', function(e) {
        e.preventDefault();
        if ($(this).hasClass('cancelEdit')) {
        	$(this).closest('td').find('.editDiv').show();
            $('#ctrls_holder').remove();
            $('.editTextArea').remove();
        }
        
        if ($(this).hasClass('saveEdit')) {
        	$(this).addClass('link-disabled').blur().html('<i class="fa fa-spinner fa-spin">&nbsp;</i>');
        	saveValue($('.editTextArea'));
        }
    });

	$('#editTable').on('keyup', '.editTextArea', function(e) {
		if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
			$('.saveEdit').click();
		}
		if( e.which === 27 ){
			$('.cancelEdit').click();
		}
	});

});

function saveValue($textarea) {
  	var params = {
   		product_id : $textarea.closest('tr').attr('data-product_id'),
   		field : $textarea.closest('tr').attr('data-field'),
   		value : $textarea.val()
   	}
   	$.post($('#editTable').attr('data-href'), params)
		.done(function(data){
            $tr = $('#ctrls_holder').closest('tr')
			$('#ctrls_holder').remove();
            $('.editTextArea').remove();
			$tr.find('.editDiv').show().text(params.value);
		})
		.fail(function(data) {})
}

function showCtrls($textarea) {
	$('#ctrls_holder').remove(); //remove old controls if exists
	var $ctrls = $('<div />').attr({'id' : 'ctrls_holder'}); //create new controls

	//control buttons  
	var $ctrlOk = $('<a href="#" class="saveEdit btn-primary" title=""><i class="fa fa-check">&nbsp;</i></a>');
	var $ctrlCancel = $('<a href="#" class="cancelEdit btn-info" title=""><i class="fa fa-times">&nbsp;</i></a>');
	$ctrls.append($ctrlOk).append($ctrlCancel);
	$textarea.before($ctrls);
}
