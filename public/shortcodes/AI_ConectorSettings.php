<?php

namespace AI_Conector\Public\Shortcodes;

use AI_Conector\Includes\AI_ConectorManager;

if (!defined('ABSPATH')) {
    exit;
}

class AI_ConectorSettings {
    public static function init() {
        add_shortcode('ai_conector_form', [__CLASS__, 'render_settings_form']);
        add_action('admin_post_ai_conector_save_settings', [__CLASS__, 'save_settings']);
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            'AI Conector',
            'AI Conector',
            'manage_options',
            'ai_conector',
            [__CLASS__, 'render_admin_page']
        );
    }

    public static function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>Configuración del Conector AI</h1>
            <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ai_conector_save_settings">
                <?php echo self::render_settings_form(); ?>
            </form>
        </div>
        <?php
    }

    public static function render_settings_form() {
        $api_keys = get_option('ai_conector_api_keys', []);
        $active_keys = get_option('ai_conector_active_keys', []);
        $failed_keys = array_diff($api_keys, $active_keys);

        $notification_email = get_option('ai_conector_notification_email', get_option('admin_email'));
        $email_notifications_enabled = get_option('ai_connector_email_notifications_enabled', true);
        $system_status = get_option('ai_conector_service_status', false);
        $status_message = self::get_system_status_message($system_status, $api_keys);

        ob_start();
        ?>
        <div class="ai-conector-form">
            <h2>Configuración del Conector AI</h2>
            <div class="system-status">
                <h3>Estado del sistema: <?php echo $system_status ? 'Activo' : 'Inactivo'; ?></h3>
                <p><strong>Mensaje de Estado:</strong> <?php echo $status_message; ?></p>
            </div>

            <label>Claves API:</label>
            <div id="api-keys-container">
                <?php foreach ($api_keys as $key) : 
                    $is_failed = in_array($key, $failed_keys);
                    $status_icon = $is_failed ? '❌' : '✔️';
                    ?>
                    <div class="api-key-entry">
                        <span class="status-icon"><?php echo $status_icon; ?></span>
                        <input type="text" name="ai_conector_api_keys[]" value="<?php echo esc_attr($key); ?>" class="<?php echo $is_failed ? 'failed-key' : ''; ?>" />
                        <button type="button" class="remove-key">Eliminar</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-key">Agregar nueva clave</button>
            
            <label>
                <input type="checkbox" name="ai_connector_email_notifications_enabled" <?php checked($email_notifications_enabled, true); ?> />
                Habilitar notificaciones por correo electrónico
            </label>
            
            <label>Correo de notificación:</label>
            <input type="email" name="ai_conector_notification_email" value="<?php echo esc_attr($notification_email); ?>" />
            
            <button type="submit" name="save_and_exit">Guardar cambios</button>
        </div>

        <style>
            .failed-key {
                border: 2px solid red;
                background-color: #ffe6e6;
            }
            .status-icon {
                font-size: 1.2em;
                margin-right: 5px;
            }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add-key').addEventListener('click', function () {
                let container = document.getElementById('api-keys-container');
                let div = document.createElement('div');
                div.classList.add('api-key-entry');
                div.innerHTML = '<span class="status-icon">✔️</span> <input type="text" name="ai_conector_api_keys[]" /> <button type="button" class="remove-key">Eliminar</button>';
                container.appendChild(div);
            });

            document.getElementById('api-keys-container').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-key')) {
                    let entries = document.querySelectorAll('.api-key-entry');
                    if (entries.length > 1) {
                        event.target.parentElement.remove();
                    } else {
                        alert("Debe haber al menos una clave.");
                    }
                }
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public static function save_settings() {
        if (isset($_POST['save_and_exit'])) {
            $api_keys = array_map('sanitize_text_field', $_POST['ai_conector_api_keys']);
            $notification_email = sanitize_email($_POST['ai_conector_notification_email']);
            $email_notifications_enabled = isset($_POST['ai_connector_email_notifications_enabled']);

            update_option('ai_conector_api_keys', $api_keys);
            update_option('ai_conector_notification_email', $notification_email);
            update_option('ai_connector_email_notifications_enabled', $email_notifications_enabled);

            AI_ConectorManager::runSystemCheck();

            wp_redirect(admin_url('admin.php?page=ai_conector'));
            exit;
        }
    }

    public static function get_system_status_message($system_status, $api_keys) {
        $active_keys = get_option('ai_conector_active_keys', []);
        $failed_keys = array_diff($api_keys, $active_keys);
        if ($system_status) {
            return empty($failed_keys) ? "Todas las claves API están operativas y funcionando correctamente." :
                   "Algunas claves API requieren atención";
        }
        return "El sistema está inactivo. Ninguna de las claves API es válida en este momento.";
    }
}



/*
Procesado con
https://chatgpt.com/share/67e70ab2-d800-8002-bccb-776f28dcfb69

*/
