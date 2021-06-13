
(function($){


    // Click process button
    $('#dcms-process-ajax').click( function(e){
        e.preventDefault();
        dcms_process_step(1);
    });

    // Process every step
    function dcms_process_step(step) {

		$.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_process_batch_ajax',
                nonce  : dcms_vars.ajaxnonce,
                step,
			},
            dataType: 'json',
			success: function(res){
                if ( res.status  == 0){
    				$('.process-info').text('Procesando paso: ' + res.step);
                    dcms_process_step(res.step)
                } else {
                    $('.process-info').text('Finalizado');
                }
			}

		});
    }

})(jQuery);

