<?php

namespace AI_Conector\Includes;

/*Este archivo se encarga del manejo  de las conexiones a la API de IA 
Esta funcion tendria que recibir absolutamente todos los parametros  que se usan en la consulta
de esa manera se podrian "ajustar y personalizar" mas los shortcodes*/

// includes/ia/ia_api_handler.php
if (!defined('ABSPATH')) {
    exit;
}

class IA_API_Handler
{
    private $active_api_key;
    private $api_url = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {

        $active_keys = get_option('ai_conector_active_keys', []);
        $this->active_api_key = !empty($active_keys) ? $active_keys[0] : null;
       
        
    }


    /*
    Atención!!  IMPORTANTE!!


    1)aqui hay que definir un flujo de trabajo para 
    pirmero de todo mirar si el servicio esta activo, si esta activo 
     que intente usar una clave si falla otra y luego devolvera 
    algo asi como servicio no disponeible , en algun momento hay que llamar o no  a sysetmManager


    2)La funcion debe tambien interpretar si busca en el buffer o no 









    */

    public function enviar_consulta($params)
    {
        // Verifica si existe la clave API
        if (!$this->active_api_key) {
            return ['error' => 'No hay clave API configurada.'];
        }

        // Construye los datos de la consulta con todos los parámetros dinámicos
        $data = [
            'model' => $params['modelo'],
            'messages' => [[
                'role' => $params['role'],
                'content' => $params['mensaje']
            ]],
            'temperature' => $params['temperature'],
            'top_p' => $params['top_p'],
            'max_tokens' => $params['max_tokens'],
            'presence_penalty' => $params['presence_penalty'],
            'frequency_penalty' => $params['frequency_penalty']
        ];

        // Agregar parámetros opcionales solo si no están vacíos
        if (!empty($params['stop'])) {
            $data['stop'] = $params['stop'];
        }
        if (!empty($params['logit_bias'])) {
            $data['logit_bias'] = $params['logit_bias'];
        }
        if (!empty($params['user'])) {
            $data['user'] = $params['user'];
        }

        // Realiza la solicitud POST a la API de OpenAI
        $response = wp_remote_post($this->api_url, [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->active_api_key,
                'Content-Type' => 'application/json'
            ],
            'timeout' => $params['timeout']
        ]);

        // Verifica si hay algún error en la solicitud HTTP
        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        // Recupera el cuerpo de la respuesta de la API
        $body = wp_remote_retrieve_body($response);

        // Devuelve la respuesta decodificada (array asociativo)
        return json_decode($body, true);
    }



    // Función para verificar si la API funciona correctamente
    public function verificar_api()
    {
        // Verifica si la clave API está configurada
        if (!$this->active_api_key) {
            return false;  // Si no hay clave API, retorna false
        }

        // Realiza una consulta simple para verificar la conexión
        $test_params = [
            'modelo' => 'gpt-3.5-turbo',
            'mensaje' => '¿Está la API funcionando correctamente?',
            'role' => 'user',
            'temperature' => 0.5,
            'top_p' => 1,
            'max_tokens' => 100,
            'presence_penalty' => 0,
            'frequency_penalty' => 0,
            'timeout' => 5 // Timeout corto para la verificación
        ];

        // Enviar la consulta a la API
        $response = $this->enviar_consulta($test_params);

        // Verifica si hubo un error en la respuesta
        if (isset($response['error'])) {
            return false;  // Si hubo error, retorna false
        }

        // Verifica si la respuesta tiene la estructura correcta
        if (!isset($response['choices'][0]['message']['content'])) {
            return false;  // Si la respuesta no tiene la estructura correcta, retorna false
        }

        // Si todo está bien, la API está funcionando correctamente
        return true;
    }



    private function test_api_key($api_key)
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
            'timeout' => 5
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $status_code = wp_remote_retrieve_response_code($response);
        return $status_code === 200;
    }
}
