<?php

namespace SimplePoll\Includes;



if (!defined('ABSPATH')) {
    exit;
}



class SPOLL_ClassPlugin
{
    public static function init() {}





    public static function activate()
    {

        //Crea la tabla para las encustas si no existe
        global $wpdb;

        $table_name = $wpdb->prefix . 'encuestas_gastronomia';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            fecha DATETIME NOT NULL,
            contenido LONGTEXT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);


        // Encuesta en blanco
        $formato_base = "ENCUESTA RESTAURANTE\n\n- Tipo de negocio: [ ] Restaurante [ ] Bar [ ] Cafetería ...\n...";
        add_option('encuesta_restaurantes_plantilla', $formato_base);



    }







    public static function deactivate()
    {
        


    }







    

    public static function uninstall()
    {


        // desinstala y borra la base de datos no hay vuelta atras
        global $wpdb;
        $table_name = $wpdb->prefix . 'encuestas_gastronomia';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");


        // Borrar opciones del sistema
        delete_option('encuesta_restaurantes_plantilla');
        delete_option('encuesta_restaurantes_progreso');


    }
}
