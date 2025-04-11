<?php

namespace AI_Conector\Includes;

use AI_Conector\api_connections\AI_ConectorApi_Functions;
use AI_Conector\Includes\AI_ConectorNotificationManager;
use AI_Conector\Logs\AI_ConectorLogger;

if (!defined('ABSPATH')) {
    exit;
}

class AI_ConectorManager
{
    private static $all_api_keys = [];
    private static $new_valid_keys = [];
    private static $anyKeyFailed = false;
    private static $allKeysFailed = true;

    public static function scheduleInit()
    {
        add_action('wp', [__CLASS__, 'maybeRunSystemCheck']);
    }

    public static function maybeRunSystemCheck()
    {
        $last_check = get_option('ai_conector_last_verification_time', '2000-01-01 00:00:00');
        $system_status = (bool) get_option('ai_conector_service_status', false);

        $current_timestamp = current_time('timestamp');
        $last_timestamp = strtotime($last_check);

        $interval = $system_status ? AI_CONECTOR_KEY_CHECK_INTERVAL_ACTIVE : AI_CONECTOR_KEY_CHECK_INTERVAL_INACTIVE;

        error_log("🛠 [AI_Conector] Último chequeo: $last_check, Estado del sistema: " . ($system_status ? 'Activo' : 'Inactivo'));
        error_log("🛠 [AI_Conector] Intervalo: $interval, Diferencia: " . ($current_timestamp - $last_timestamp));


        if (($current_timestamp - $last_timestamp) > $interval) {
            self::runSystemCheck();
            update_option('ai_conector_last_verification_time', date('Y-m-d H:i:s'));
        }
    }

    public static function runSystemCheck()
    {
        self::init();
        self::checkKeys();
        error_log("🔄 [Antes de rotar] Claves activas: " . print_r(get_option('ai_conector_active_keys'), true));
        self::rotateKey();
        error_log("🔄 [Después de rotar] Claves activas: " . print_r(get_option('ai_conector_active_keys'), true));

        if (self::$allKeysFailed) {
            AI_ConectorNotificationManager::sendNotification(true);
            self::setSystemInactive();
        } else {
            if (self::$anyKeyFailed) {
                AI_ConectorNotificationManager::sendNotification(false);
            }
            self::setSystemActive();
        }
    }

    private static function init()
    {
        self::$all_api_keys = get_option('ai_conector_api_keys', []);
        self::$new_valid_keys = [];
        self::$anyKeyFailed = false;
        self::$allKeysFailed = true;
    }

    private static function checkKeys()
    {
        $valid_keys = [];

        error_log("🔑 [AI_Conector] Comenzando validación de claves. Total: " . count(self::$all_api_keys));

        foreach (self::$all_api_keys as $key) {

            $is_valid = AI_ConectorApi_Functions::test_api_key($key);
            error_log("🔍 Clave: $key - " . ($is_valid ? "Válida ✅" : "Inválida ❌"));
    

            if ($is_valid) {
                $valid_keys[] = $key;
                
                self::$allKeysFailed = false;   
            } else {
                self::$anyKeyFailed = true;
            }
        }

        self::$new_valid_keys = $valid_keys;
        update_option('ai_conector_active_keys', $valid_keys);
    }

    private static function rotateKey()
    {
        if (!empty(self::$new_valid_keys)) {
            shuffle(self::$new_valid_keys);
            update_option('ai_conector_active_keys', self::$new_valid_keys);
        }
    }

    private static function setSystemInactive()
    {
        update_option('ai_conector_service_status', false);
    }

    private static function setSystemActive()
    {
        update_option('ai_conector_service_status', true);
    }
}
