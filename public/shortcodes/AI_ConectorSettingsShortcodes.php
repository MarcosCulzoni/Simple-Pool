<?php

namespace AI_Conector\Public\Shortcodes;

//use AI_Conector\Includes\IA_API_Handler;
//use AI_Conector\Includes\IA_Responses_Processor;

if (!defined('ABSPATH')) {
    exit;
}


class AI_ConectorSettingsShortcodes
{


    public static function init()
    {


        add_shortcode('ai-conector-settings', [__CLASS__, 'render_shortcode']);

        //Carga el JavaScript en el frontend
        add_action('wp_enqueue_scripts', [__CLASS__, 'load_public_assets']);

        //Maneja AJAX para usuarios logueados
        add_action('wp_ajax_ai_conector_save_settings', [__CLASS__, 'save_settings']);
        //Maneja AJAX para usuarios no logueados
        add_action('wp_ajax_nopriv_ai_conector_save_settings', [__CLASS__, 'save_settings']);
    }

    public static function render_shortcode()
    {
        ob_start();
        $api_primary = esc_attr(get_option('AI_Conector_api_primary', ''));
        $api_backup = esc_attr(get_option('AI_Conector_api_backup', ''));
        // $active_key = get_option('AI_Conector_api_primary_active', 'true');
        $active_key = get_option('AI_Conector_api_primary_active', true)  ? 'primary' : 'backup';


        $notification_email = esc_attr(get_option('AI_Conector_notification_email', ''));
        // No necesito recuperar last e-mail notification
        $service_status = esc_attr(get_option('AI_Conector_service_status', true));



?>
        <form id="ai-conector-form">
            <label>API Primary Key:</label>
            <input type="text" name="ai_conector_api_primary" value="<?php echo $api_primary; ?>"><br>

            <label>API Backup Key:</label>
            <input type="text" name="ai_conector_api_backup" value="<?php echo $api_backup; ?>"><br>

            <label>Clave Activa:</label>
            <select name="ai_conector_api_primary_active">
                <option value="primary" <?php selected($active_key, 'primary'); ?>>Primary</option>
                <option value="backup" <?php selected($active_key, 'backup'); ?>>Backup</option>
            </select><br>

            <label>Email de Notificación:</label>
            <input type="email" name="ai_conector_notification_email" value="<?php echo $notification_email; ?>"><br>

            <button type="submit">Guardar</button>
            <p id="ai-conector-message"></p>
        </form>

        <script>
            document.getElementById('ai-conector-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'ai_conector_save_settings');

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('ai-conector-message').innerText = data.data.message;
                        } else {
                            document.getElementById('ai-conector-message').innerText = 'Error: ' + (data.data?.message || 'No se pudo guardar');
                        }
                    })
                    .catch(error => {
                        document.getElementById('ai-conector-message').innerText = 'Error al guardar la configuración';
                    });
            });
        </script>
<?php
        return ob_get_clean();
    }



    public static function save_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'No tienes permisos']);
        }

        update_option('AI_Conector_api_primary', sanitize_text_field($_POST['ai_conector_api_primary']));
        update_option('Ai_Conector_api_backup', sanitize_text_field($_POST['ai_conector_api_backup']));


        $value = ($_POST['ai_conector_api_primary_active'] === 'primary') ? true : false;
        update_option('AI_Conector_api_primary_active', sanitize_text_field($value));

        //update_option('ai_conector_api_primary_active', sanitize_text_field($_POST['ai_conector_api_primary_active']));
        update_option('AI_Conector_notification_email', sanitize_email($_POST['ai_conector_notification_email']));


        wp_send_json_success(['message' => 'Configuración guardada correctamente']);
    }

    public static function load_public_assets()
    {
        wp_enqueue_style('ai-conector-public-style', plugin_dir_url(__FILE__) . '../../assets/css/public.css');
    }
}


