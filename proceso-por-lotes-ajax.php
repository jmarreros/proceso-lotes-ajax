<?php
/*
Plugin Name: Proceso por lotes ajax
Plugin URI: https://decodecms.com
Description: Plugin para demostración de proceso por lotes con Ajax
Version: 1.0
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
License: MIT
*/

// Creación del ítem de menú en menú Herramientas
add_action( 'admin_menu', 'dcms_batch_process_ajax_menu' );

function dcms_batch_process_ajax_menu() {
	add_management_page( 'Proceso por lotes Ajax', 'Proceso por lotes Ajax', 'manage_options', 'dcms_batch_process_ajax', 'dcms_batch_process_ajax_options' );
}

function dcms_batch_process_ajax_options(){
    echo "<div class='wrap'>";
    echo "<a id ='dcms-process-ajax' class='button button-primary' href='#'>Procesar</a>";
    echo "</div><hr/>";
    echo "<div class='process-info'></div>";
}


//Insertar Javascript en la administración
add_action('admin_enqueue_scripts', 'dcms_insertar_js');

function dcms_insertar_js(){
	wp_register_script('dcms_script', plugins_url( 'script.js', __FILE__ ), array('jquery'), '1.0', true );
	wp_enqueue_script('dcms_script');


	wp_localize_script('dcms_script','dcms_vars',[
            'ajaxurl'=>admin_url('admin-ajax.php'),
            'ajaxnonce' => wp_create_nonce('dcms-ajax-batch-nonce'),
        ]);
}

// Procesar el batch por Ajax
add_action('wp_ajax_dcms_process_batch_ajax','dcms_process_batch_ajax');

function dcms_process_batch_ajax(){

    $batch  = 100;
    $total  = $_REQUEST['total']??false;
    $step   = $_REQUEST['step']??0;
    $count  = $step*$batch;
    $status = 0;

    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'dcms-ajax-batch-nonce_')) {
        error_log('Error de Nonce!');
        return;
    }

    // Procesamos la información
    sleep(0.5);
    error_log("step: ". $step ." - count: ". $count);
    // ----

    $step++;

    // Otenemos el total
    if ( ! $total ) {
        $total = dcms_get_total_ajax();
    }

    // Comprobamos la finalización
    if ( $count < $total ){
        $status = 0;
    } else {
        $status = 1;
    }

    // Construimos la url
    $args = array_merge($_REQUEST, [
        'step'  => $step,
        'total' => $total,
        'nonce' => wp_create_nonce( 'process-batch-ajax' ),
    ]);
    $url = add_query_arg( $args, admin_url() );


    // Construimos la respuesta
    $res = [
        'status'  => $status,
        'step'    => $step,
        'url'     => $url,
    ];

	echo json_encode($res);

	wp_die();
}

// Función auxiliar para devolver el total de registros
function dcms_get_total_ajax(){
    return 500;
}
