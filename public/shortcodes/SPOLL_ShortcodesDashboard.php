<?php

namespace SimplePoll\Public\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}

use \wpdb;

// ------------------------Clase principal para la inicialización
class SPOLL_ShortcodesDashboard
{
    public static function init()
    {
        add_shortcode('mostrar_encuestas', [self::class, 'render_encuestas_y_exportar']);
    }

    

    public static function render_encuestas_y_exportar()
    {
        global $wpdb;
        $tabla = $wpdb->prefix . "encuestas_gastronomia";

        $encuestas = $wpdb->get_results("SELECT id, fecha FROM $tabla ORDER BY fecha DESC");

        ob_start();

        echo "<div style='text-align: center;'>";

        echo "<ul style='list-style: none; padding: 0;'>";
        foreach ($encuestas as $encuesta) {
            echo "<li>Encuesta del: " . esc_html($encuesta->fecha) . "</li>";
        }
        echo "</ul>";

        $url_exportar = esc_url(admin_url('admin-post.php?action=exportar_encuestas_txt'));

        echo "<form method='post' action='$url_exportar'>
            <button type='submit' style='margin-top: 20px;'>Exportar todas las encuestas</button>
          </form>";

        echo "</div>";

        return ob_get_clean();
    }
}
