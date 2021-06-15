
(function($){


    // Click process button
    $('#dcms-process-ajax').click( function(e){
        e.preventDefault();
        dcms_process_step(1);
    });

    // Process every step
    function dcms_process_step(step, total = null) {

		$.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_process_batch_ajax',
                nonce  : dcms_vars.ajaxnonce,
                total,
                step,
			},
            dataType: 'json',
			success: function(res){
                if ( res.status  == 0){
    				$('.process-info').html(`<strong>Procesados ${res.count} de ${res.total}
                                            <br> Paso: ${res.step}</strong>`);
                    dcms_process_step(res.step, res.total)
                } else {
                    $('.process-info').text('Finalizado');
                }
			}

		});
    }

})(jQuery);

