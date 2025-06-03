<?php

namespace SimplePoll\Includes;

if (!defined('ABSPATH')) {
    exit;
}



class SPOLL_ClassPlugin
{


    public static function activate()
    {
        error_log('Se ha activado SimplePoll');

        //Crea la tabla para las encuestas si no existe
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

        //Registrar posibles errores
        if ($wpdb->last_error) {
            error_log("Error creando la tabla: " . $wpdb->last_error);
        }


        // Verificar si la tabla se creó correctamente
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
        if (!$table_exists) {
            error_log("Error crítico: La tabla $table_name no se creó correctamente.");
        } else {
            error_log("La tabla $table_name se creó o ya existía.");
        }



        // Encuesta en blanco
        $formato_base = "🧾 FORMULARIO DE ENCUESTA COMIENZO
Instrucciones: Marcar con una “X” las opciones que correspondan y completar los campos de texto donde se indica.

🔹 1. DATOS BÁSICOS DEL NEGOCIO
Tipo de negocio:
[ ] Restaurante
[ ] Bar
[ ] Cafetería
[ ] Food Truck
[ ] Panadería
[ ] Otro

Ciudad: [escribir nombre]

Zona:
[ ] Centro
[ ] Barrio
[ ] Zona turística
[ ] Paseo marítimo
[ ] Otro

Tamaño del local (número de mesas):
[ ] 1-10
[ ] 11-30
[ ] Más de 30

Público objetivo:
[ ] Predominan turistas
[ ] Predominan locales
[ ] 50%-50%

Tipo de restaurante:
[ ] Familiar
[ ] Gourmet
[ ] Comida rápida
[ ] Casual informal

🔹 2. TECNOLOGÍA UTILIZADA
¿Tienen página web?
[ ] Sí
[ ] No

Última actualización de la web: [fecha o “No aplica”]

Plataforma de la web:
[ ] WordPress
[ ] Wix
[ ] Shopify
[ ] Otra
[ ] No tiene

Sistema de facturación y gestión: [escribir nombre o “No utiliza”]

Canales de comunicación con clientes:
[ ] Redes sociales
[ ] WhatsApp
[ ] Email
[ ] Teléfono
[ ] Otros

🔹 3. FUNCIONALIDADES QUE LE INTERESAN AL RESTAURANTE
Marcar con “X” todas las funcionalidades que les interesen:

[ ] 1. Carta digital (acceso por QR, multidioma, opciones visuales/auditivas)
[ ] 2. Carta interactiva con IA (filtros como vegano/sin gluten, recomendaciones, traducción automática)
[ ] 3. Reservas automáticas (gestión de mesas, confirmaciones por SMS/WhatsApp, integración con Google Calendar)
[ ] 4. Gestión de redes sociales (programación de publicaciones, respuestas automáticas, integración con redes)
[ ] 5. Contacto y atención al cliente (formulario web, WhatsApp, chatbot con IA)
[ ] 6. Fidelización de clientes (cupones, descuentos, sorteos, programa de puntos)
[ ] 7. Pedidos online y delivery (Click & Collect, delivery propio, pagos digitales)
[ ] 8. Gestión de eventos y promociones (eventos especiales, promociones, integración con redes)
[ ] 9. Reportes y análisis (ventas, reservas, análisis de datos con IA)
[ ] 10. Marketing (newsletters, promociones segmentadas, WhatsApp y email marketing)
[ ] 11. Encuestas y opiniones (encuestas automáticas, gestión de reseñas, análisis de satisfacción)
[ ] 12. Chatbot con IA (respuestas automáticas, atención 24/7, integración con reservas y pedidos)

🔹 4. TU WEB Y PRESENCIA ONLINE
Marcar con “X” lo que corresponda y completar donde se indica:

¿Cuándo fue la última vez que actualizaron su web?
[ ] En los últimos 3 meses
[ ] Entre 3 y 12 meses
[ ] Hace más de un año
[ ] No tienen página web

¿Qué tan importante consideran la web para atraer clientes?
[ ] Nada
[ ] Algo
[ ] Mucho

¿Usan herramientas pagas para alguno de estos aspectos?
[ ] Reservas
[ ] Redes sociales
[ ] Facturación
[ ] No usan herramientas pagas

Si pudieran mejorar algo en su web, ¿qué sería?
[Respuesta abierta]

🔹 5. OTRAS NECESIDADES Y COMENTARIOS
¿Hay alguna funcionalidad que te gustaría y no mencionamos?
[Respuesta abierta]

Comentarios adicionales:
[Respuesta abierta]

✨ FIN DE ENCUESTA [Negocio X] ✨
";
        update_option('encuesta_restaurantes_plantilla', $formato_base);
    }








    public static function deactivate()
    {

        error_log('Se ha desactivado Simple Pool');
        // Borrar opciones del sistema
        delete_option('encuesta_restaurantes_plantilla');
        delete_option('encuesta_restaurantes_progreso');
    }









    public static function uninstall()
    {

        error_log('Se ha desinstaldo SimplePoll');

        // desinstala y borra la base de datos no hay vuelta atras
        global $wpdb;
        $table_name = $wpdb->prefix . 'encuestas_gastronomia';
        //$wpdb->query("DROP TABLE IF EXISTS $table_name");



        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
        if ($table_exists) {
            $wpdb->query("DROP TABLE IF EXISTS $table_name");
            error_log("Tabla $table_name eliminada correctamente.");
        } else {
            error_log("La tabla $table_name no existe, nada que eliminar.");
        }
    }
}
