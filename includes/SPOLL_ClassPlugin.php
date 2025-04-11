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
        // Inicialización de las opciones si no existen
        add_option('ai_conector_service_status', false); // Estado del plugin, inactivo inicialmente

        // Lista de claves API predeterminadas
        $default_keys = [
           


        ];
        add_option('ai_conector_api_keys', $default_keys); // Guarda las claves predeterminadas
        add_option('ai_conector_last_emailnotification', '2000-01-01 00:00:00');

        // Clave activa inicialmente vacía
        add_option('ai_conector_active_keys', []); // Guarda la lista de claves activas (vacía al principio)
        

        // Configuración de notificaciones
        $admin_email = get_option('admin_email'); // Obtiene el email del administrador
        add_option('ai_conector_notification_email', $admin_email); // Guardar email del administrador para notificaciones
        add_option('ai_connector_email_notifications_enabled', true); // Habilita notificaciones por email
        add_option('ai_conector_last_emailnotification', '2000-01-01 00:00:00'); // Fecha inicial muy antigua para notificaciones



        // Chequiar claves rotar y activar cronjobs etc
        AI_ConectorManager::runSystemCheck();
        
    }





    

    public static function deactivate()
    {
        delete_option('ai_conector_api_keys');
        // Puedes agregar acciones para desactivar el plugin aquí si es necesario
    }

    public static function uninstall()
    {


        /*
        delete_option('AI_Conector_api_backup');
        delete_option('AI_Conector_api_primary_active');
        delete_option('AI_Conector_notification_email');
        delete_option('AI_Conector_last_emailnotification');
        delete_option('AI_Conector_service_status');
        */
    }
}
