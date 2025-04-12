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

        echo "<div class='encuestas-listado'>";
        echo "<ul>";
        foreach ($encuestas as $encuesta) {
            echo "<li>Encuesta del: " . esc_html($encuesta->fecha) . "</li>";
        }
        echo "</ul>";
        echo "</div>";

        $url_exportar = esc_url(admin_url('admin-post.php?action=exportar_encuestas_txt'));

        echo "<form method='post' action='$url_exportar'>
                <button type='submit'>Exportar todas las encuestas</button>
              </form>";

        return ob_get_clean();
    }
}

