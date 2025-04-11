<?php

namespace AI_Conector\api_connections;
use AI_Conector\Logs\AI_ConectorLogger;

if (!defined('ABSPATH')) {
    exit;
}



class AI_ConectorResponseProcesor {

    public static function procesar_respuesta($response) {
        if (isset($response['error'])) {
            return 'Error: ' . $response['error'];
        }

        if (!isset($response['choices'][0]['message']['content'])) {
            return 'No se recibió una respuesta válida de la IA.';
        }

        return $response['choices'][0]['message']['content'];
    }
    
}



