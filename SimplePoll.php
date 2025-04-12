<?php
/**
 * Plugin Name: SimplePoll
 * Description: An ultra-basic plugin to create plain text surveys online, with outputs ready for AI analysis.
 * Version: 1.0
 * Author: Marcos Culzoni
 */


if (!defined('ABSPATH')) {
    exit;
}


// Definir constantes
define('SPOLL_VERSION', '1.0');
define('SPOLL_PREFIX', 'SPOLL_'); // Prefijo estandarizado

// Rutas del plugin
define('SPOLL_PLUGIN_PATH', plugin_dir_path(__FILE__)); 
define('SPOLL_PLUGIN_URL', plugin_dir_url(__FILE__));  

// Cargar archivos de clase, despues de esto ya referencia las clases usando los espacios de nombre
require_once SPOLL_PLUGIN_PATH . 'public/shortcodes/SPOLL_ShortcodesDashboard.php';
require_once SPOLL_PLUGIN_PATH .  'public/shortcodes/SPOLL_Shortcodes.php';
require_once SPOLL_PLUGIN_PATH . 'includes/SPOLL_ClassPlugin.php';
require_once SPOLL_PLUGIN_PATH . 'includes/SPOLL_Public-Functions.php';

// Registrar hooks principales
register_activation_hook(__FILE__, ['SimplePoll\Includes\SPOLL_ClassPlugin', 'activate']);
register_deactivation_hook(__FILE__, ['SimplePoll\Includes\SPOLL_ClassPlugin', 'deactivate']);
register_uninstall_hook(__FILE__, ['SimplePoll\Includes\SPOLL_ClassPlugin', 'uninstall']);

// Inicializar el plugin
function SPOLL__run_plugin()
{
    SimplePoll\Includes\SPOLL_ClassPlugin::init();
    SimplePoll\Public\Shortcodes\SPOLL_Shortcodes::init();
    SimplePoll\Public\Shortcodes\SPOLL_ShortcodesDashboard::init();

}

// Asegura que las dependencias de otros plugins estén cargadas antes de inicializar este plugin.
add_action('plugins_loaded', 'SPOLL__run_plugin');
