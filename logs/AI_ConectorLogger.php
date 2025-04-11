<?php

namespace AI_Conector\Logs;

if (!defined('ABSPATH')) {
    exit;
}

class AI_ConectorLogger
{
    private static $log_prefix = 'AI_Conector';
    private static $log_dir = AI_CONECTOR_PLUGIN_PATH . 'logs/';
    private static $log_file = AI_CONECTOR_PLUGIN_PATH . 'logs/ai-conector-log.txt';

    // Método para escribir en el log
    public static function log($level, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "$timestamp,$level," . self::$log_prefix . ",$message\n";

        // Crear carpeta de logs si no existe
        if (!file_exists(self::$log_dir)) {
            if (!mkdir(self::$log_dir, 0777, true) && !is_dir(self::$log_dir)) {
                error_log("AI_ConectorLogger: No se pudo crear el directorio de logs.");
                return;
            }
        }

        // Guardar en el archivo de log con control de errores
        if (file_put_contents(self::$log_file, $log_message, FILE_APPEND) === false) {
            error_log("AI_ConectorLogger: No se pudo escribir en el archivo de log.");
        }
    }

    // Método para borrar el archivo de log
    public static function clear_log()
    {
        if (file_exists(self::$log_file) && !unlink(self::$log_file)) {
            error_log("AI_ConectorLogger: No se pudo eliminar el archivo de log.");
        }
    }

    // Método para archivar el log
    public static function archive_log()
    {
        if (file_exists(self::$log_file)) {
            $timestamp = date('Y-m-d_H-i-s');
            $archive_file = self::$log_dir . 'ai-conector-log-' . $timestamp . '.txt';

            if (!rename(self::$log_file, $archive_file)) {
                error_log("AI_ConectorLogger: No se pudo archivar el log.");
                return;
            }

            // Crear un nuevo archivo vacío
            file_put_contents(self::$log_file, '');
        }
    }
}


    /*
                VALORES COMUNES QUE SE PRODRÍAN USAR EN $level
    debug → Para mensajes de depuración, detalles internos útiles para desarrolladores.
    info → Para registrar eventos informativos generales, como acciones completadas con éxito.
    notice → Para avisos que no son errores pero que podrían ser importantes.
    warning → Para advertencias que podrían necesitar atención, pero no son errores graves.
    error → Para errores que afectan la funcionalidad pero no detienen la ejecución.
    critical → Para errores críticos que pueden afectar severamente el sistema.
    alert → Para problemas urgentes que necesitan intervención inmediata.
    emergency → Para fallos graves que hacen que el sistema sea inutilizable.
    */
