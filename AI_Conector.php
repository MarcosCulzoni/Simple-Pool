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
define('AI_CONECTOR_VERSION', '1.0');
define('AI_CONECTOR_PREFIX', 'SPOOL_'); // Prefijo estandarizado


// Rutas del plugin
define('SPOOL_PLUGIN_PATH', plugin_dir_path(__FILE__)); 
define('SPOOL_PLUGIN_URL', plugin_dir_url(__FILE__));  

// Cargar archivos de clase


require_once AI_CONECTOR_PLUGIN_PATH . 'public/shortcodes/AI_ConectorShortcodes.php';
require_once AI_CONECTOR_PLUGIN_PATH . 'includes/AI_ConectorClassPlugin.php';



//require_once AI_CONECTOR_PLUGIN_PATH . 'admin/AI_ConectorClassAdmin.php';
//require_once AI_CONECTOR_PLUGIN_PATH . 'public/shortcodes/AI_ConectorA_MenuShortcodes.php';
//require_once AI_CONECTOR_PLUGIN_PATH . 'public/shortcodes/AI_ConectorSettingsShortcodes.php'; //SE ELIMINARAN!!

//require_once AI_CONECTOR_PLUGIN_PATH . 'public/shortcodes/AI_ConectorSettings.PHP';

//require_once AI_CONECTOR_PLUGIN_PATH . 'logs/AI_ConectorLogger.php';



//require_once AI_CONECTOR_PLUGIN_PATH . 'includes/api_conections/AI_ConectorResponseProcesor.php';
//require_once AI_CONECTOR_PLUGIN_PATH . 'includes/api_conections/AI_ConectorApiFuncitions.php';


//require_once AI_CONECTOR_PLUGIN_PATH . 'includes/AI_ConectorNotificationManager.php';

//require_once AI_CONECTOR_PLUGIN_PATH . 'includes/AI_ConectorManager.php';


// Registrar hooks principales
register_activation_hook(__FILE__, ['SimplePoll\Includes\AI_ConectorClassPlugin', 'activate']);
register_deactivation_hook(__FILE__, ['AI_Conector\Includes\AI_ConectorClassPlugin', 'deactivate']);
register_uninstall_hook(__FILE__, ['AI_Conector\Includes\AI_ConectorClassPlugin', 'uninstall']);

// Inicializar el plugin
function ai_conector_run_plugin()
{
    AI_Conector\Includes\AI_ConectorClassPlugin::init();
    AI_Conector\Admin\AI_ConectorClassAdmin::init();
    AI_Conector\Public\Shortcodes\AI_ConectorShortcodes::init();
    AI_Conector\Includes\AI_ConectorManager::scheduleInit(); // Para que se registre el hook
}

// Asegura que las dependencias de otros plugins estén cargadas antes de inicializar este plugin.
add_action('plugins_loaded', 'ai_conector_run_plugin');

// Cargar CSS en el admin
function ai_conector_enqueue_admin_styles()
{
    wp_enqueue_style('ai_conector_admin_css', plugins_url('assets/css/admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'ai_conector_enqueue_admin_styles');
