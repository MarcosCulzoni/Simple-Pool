<?php

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Hook para usuarios logueados y no logueados
add_action('admin_post_exportar_encuestas_txt', 'spoll_exportar_encuestas_txt');
add_action('admin_post_nopriv_exportar_encuestas_txt', 'spoll_exportar_encuestas_txt');

// Función que exporta las encuestas
function spoll_exportar_encuestas_txt() {
    global $wpdb;

    while (ob_get_level()) {
        ob_end_clean();
    }

    $tabla = $wpdb->prefix . "encuestas_gastronomia";
    $encuestas = $wpdb->get_results("SELECT * FROM $tabla ORDER BY fecha ASC");

    $output = "";

    foreach ($encuestas as $encuesta) {
        $output .= "ID: $encuesta->id\n";
        $output .= "Fecha: $encuesta->fecha\n\n";
        $output .= $encuesta->contenido . "\n";
        $output .= "\n-----------------------------\n\n";
    }

    header('Content-Description: File Transfer');
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="encuestas_exportadas.txt"');
    header('Content-Length: ' . strlen($output));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    echo $output;
    exit;
}
