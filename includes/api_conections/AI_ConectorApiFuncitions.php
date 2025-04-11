<?php

namespace AI_Conector\api_connections;

use AI_Conector\Logs\AI_ConectorLogger;

if (!defined('ABSPATH')) {
    exit;
}

class AI_ConectorApi_Functions
{
    /* 
    Esta función verifica si una clave de API proporcionada es válida y si puede establecer
    una comunicación exitosa con el servicio de OpenAI. Realiza una solicitud de prueba a la API
    de OpenAI para verificar que la clave de API esté activa y funcione correctamente.

    La función devuelve `true` si la clave API es válida y la respuesta de la API es exitosa (código de 
    estado HTTP 200), y `false` en cualquier otro caso, como cuando la clave es inválida, la solicitud 
    falla o el servidor no responde correctamente. No se especifica el tipo exacto de error en caso 
    de fallo.

    Parámetros:
    - $api_key (string): La clave de API que se desea verificar.

    Retorna:
    - bool: `true` si la clave de API es válida y la comunicación fue exitosa, `false` en caso contrario.
    */

    public static function test_api_key($api_key)
    {
        if (empty($api_key)) {
            return false;
        }

        $test_params = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [['role' => 'user', 'content' => 'Test de API']],
            'max_tokens' => 5
        ];

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'body' => json_encode($test_params),
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            AI_ConectorLogger::log("debuging", "La clave api no funciona 'is_wp_erro'");

            return false;
        }

        $status_code = wp_remote_retrieve_response_code($response);

        // Se modifico el final para poderlo depurar mas facilmente
        if ($status_code === 200) {
            AI_ConectorLogger::log("debuging", "La clave api funciona  el status code es $status_code");
            return true;
        } else {
            AI_ConectorLogger::log("debuging", "La clave api NO funciona  el status code es $status_code");
            return false;
        }
    }
}
