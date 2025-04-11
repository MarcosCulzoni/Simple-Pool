<?php
namespace AI_Conector\Public\Shortcodes;

use AI_Conector\Includes\IA_API_Handler;
use AI_Conector\Includes\IA_Responses_Processor;
use AI_Conector\Logs\AI_ConectorLogger;

if (!defined('ABSPATH')) {
    exit;
}


class AI_ConectorA_MenuShortcodes
{
    public static function init()
    {
        //Se registra el ShortCode en Word Press
        add_shortcode('ia_respuesta', [__CLASS__, 'shortcode_ia_respuesta']); // Ejemplo de short code -> [ia_respuesta pregunta="Define democracia" modelo="gpt-3.5-turbo"]
    }
//[__CLASS__, 'render_shortcode']

    public static function shortcode_ia_respuesta($atts)
    {
        //AI_ConectorLogger::log("debug","Se llamo a un shortcode de A_Menu");
        //AI_ConectorLogger::clear_log();
        //AI_ConectorLogger::archive_log();

        // Establece valores por defecto para los atributos
        $atts = shortcode_atts([
            'pregunta' => 'Hola, ¿cómo estás?',
            'modelo' => 'gpt-3.5-turbo', // Otro valor posible para 'modelo' es 'gpt-4o-mini'
        ], $atts);

        // Crear una instancia del manejador de IA
        $ia_handler =  new IA_API_Handler();

        // Definir todos los parámetros disponibles para la API
        $params = [
            'mensaje' => $atts['pregunta'], // Mensaje del usuario
            'modelo' => $atts['modelo'], // Modelo de IA a usar
            'role' => 'user', // Rol del usuario
            'temperature' => 0.7, // Aleatoriedad de la respuesta
            'top_p' => 1, // Nucleic sampling (valores entre 0 y 1)
            'max_tokens' => 1000, // Límite de tokens en la respuesta
            'presence_penalty' => 0, // Penalización por introducir nuevos temas
            'frequency_penalty' => 0, // Penalización por repetir frases
            'stop' => null, // Secuencia de parada (opcional)
            'logit_bias' => null, // Sesgo en tokens específicos (opcional)
            'user' => null, // ID del usuario (opcional)
            'timeout' => 10 // Tiempo máximo de espera para la respuesta
        ];

        // Enviar la consulta a la API
        $respuesta = $ia_handler->enviar_consulta($params);

        return IA_Responses_Processor::procesar_respuesta($respuesta);
    }







    public static function shortcode_ia_respuesta_dos($atts)
    {
        // Establece valores por defecto para los atributos
        $atts = shortcode_atts([
            'pregunta' => 'Hola, ¿cómo estás?',
            'modelo' => 'gpt-3.5-turbo', // Otro valor posible para 'modelo' es 'gpt-4o-mini'
            'mensaje_espera' => 'Se está ejecutando la busqueda',
            'mensaje_error' => 'Fué imposible conectarse con la fuente',
        ], $atts);

        // Crear una instancia del manejador de IA
        $ia_handler =  new IA_API_Handler();

        // Definir todos los parámetros disponibles para la API
        $params = [
            'mensaje' => $atts['pregunta'], // Mensaje del usuario
            'modelo' => $atts['modelo'], // Modelo de IA a usar
            'role' => 'user', // Rol del usuario
            'temperature' => 0.7, // Aleatoriedad de la respuesta
            'top_p' => 1, // Nucleic sampling (valores entre 0 y 1)
            'max_tokens' => 1000, // Límite de tokens en la respuesta
            'presence_penalty' => 0, // Penalización por introducir nuevos temas
            'frequency_penalty' => 0, // Penalización por repetir frases
            'stop' => null, // Secuencia de parada (opcional)
            'logit_bias' => null, // Sesgo en tokens específicos (opcional)
            'user' => null, // ID del usuario (opcional)
            'timeout' => 10 // Tiempo máximo de espera para la respuesta
        ];

        // Enviar la consulta a la API
        $response = $ia_handler->enviar_consulta($params);

         // Si hay un error en la respuesta o no está bien estructurada
         if (!isset($response['choices'][0]['message']['content']) || isset($response['error'])) {
            return $atts['mensaje_error'];
        }

        // Devolver la respuesta de la IA si todo está bien
        return $response['choices'][0]['message']['content'];
    }


    //Seguir con esto en   https://chatgpt.com/share/67db16bb-91e0-800f-b270-92f414998796


        
    }





