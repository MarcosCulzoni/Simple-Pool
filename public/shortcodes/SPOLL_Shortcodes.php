<?php

namespace SimplePoll\Public\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}


// ------------------------Clase principal para la inicializacion de los shortcodes
class SPOLL_Shortcodes
{
    public static function init()
    {


        add_shortcode('spoll_form', [self::class, 'render_form']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);

        // Se registran las acciones AJAX para manejar las peticiones guardar enviar y descartar encuestas
        add_action('wp_ajax_nopriv_spoll_guardar', [self::class, 'guardar_encuesta']);
        add_action('wp_ajax_nopriv_spoll_enviar', [self::class, 'enviar_encuesta']);
        add_action('wp_ajax_nopriv_spoll_descartar', [self::class, 'descartar_encuesta']);


        add_action('wp_ajax_spoll_guardar', [self::class, 'guardar_encuesta']);
        add_action('wp_ajax_spoll_enviar', [self::class, 'enviar_encuesta']);
        add_action('wp_ajax_spoll_descartar', [self::class, 'descartar_encuesta']);
    }






    public static function enqueue_assets()
    {
        error_log('🔧 Enqueue_assets ejecutado');

        wp_enqueue_script('spoll-script', SPOLL_PLUGIN_URL . 'public/assets/spoll.js', ['jquery'], SPOLL_VERSION, true);

        wp_localize_script('spoll-script', 'spoll_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('spoll_nonce')
        ]);
    }





    public static function render_form()
    {
        $contenido = get_option('encuesta_restaurantes_progreso');

        if (!$contenido) {
            $contenido = get_option('encuesta_restaurantes_plantilla');
        }

        ob_start(); ?>
        <div id="spoll-encuesta-wrap">
            <textarea id="spoll-contenido" rows="25" style="width:100%;"><?php echo esc_textarea($contenido); ?></textarea>

            <div style="margin-top:10px; display: flex; justify-content: center; gap: 10px;">
                <button id="spoll-btn-descartar">🧹 Discard / Start New </button>
                <button id="spoll-btn-guardar">💾 Save / Continue Later</button>
                <button id="spoll-btn-enviar">✅ Finish and Submit</button>
            </div>

            <div id="spoll-mensaje" style="margin-top:10px;"></div>
        </div>

<?php
        return ob_get_clean();
    }







    public static function guardar_encuesta()
    {
        check_ajax_referer('spoll_nonce', 'nonce');

        $contenido = sanitize_textarea_field($_POST['contenido']);
        update_option('encuesta_restaurantes_progreso', $contenido);

        wp_send_json_success('Encuesta guardada.');
    }




    public static function enviar_encuesta()
    {
        check_ajax_referer('spoll_nonce', 'nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'encuestas_gastronomia';
        $contenido = sanitize_textarea_field($_POST['contenido']);

        $wpdb->insert($table, [
            'fecha' => current_time('mysql'),
            'contenido' => $contenido
        ]);

        delete_option('encuesta_restaurantes_progreso');

        wp_send_json_success('Encuesta enviada con éxito.');
    }



    

    public static function descartar_encuesta()
    {
        check_ajax_referer('spoll_nonce', 'nonce');

        delete_option('encuesta_restaurantes_progreso');

        $plantilla = get_option('encuesta_restaurantes_plantilla');
        wp_send_json_success($plantilla);
    }
}