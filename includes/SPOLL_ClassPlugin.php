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
        $formato_base = "🧠 Sección Inicial – Observaciones del Encuestador (opcional)







🧩 Sección 1 – Presentación y Objetivo de la Encuesta

Queremos conocer de primera mano los problemas reales que enfrentan bares, restaurantes y cafeterías, para desarrollar herramientas más útiles, tanto clásicas como basadas en inteligencia artificial.  
Esta encuesta es breve (menos de 10 minutos), anónima y nos ayuda a diseñar soluciones que ahorren tiempo y dinero.

---

🧩 Sección 2 – Información Básica del Negocio

1. Tipo de negocio (elige una opción numérica):  
   1. Restaurante  
   2. Bar  
   3. Cafetería  
   4. Food Truck  
   5. Panadería / Pastelería  
   6. Otro  
   Respuesta: _______

2. Ciudad:  
   Respuesta: _______

3. Zona (elige una opción numérica):  
   1. Casco antiguo / Centro histórico
   2. Zona residencial
   3. Zona turística o de alto tránsito
   4. Zona costera (paseo marítimo / playa)
   5. Otra (especificar): __________
   Respuesta: _______

4. Tamaño del local (número aproximado de mesas):

   1–5
   6–10
   11–20
   21–40
   Más de 40	
   Respuesta: _______



5. Público objetivo (elige una opción numérica):  
   1. Predominan turistas  
   2. Predominan locales  
   3. 50% turistas / 50% locales  
   Respuesta: _______

6. Tipo de experiencia gastronómica (elige una opción numérica):
   1. Familiar / tradicional
   2. Gourmet / alta cocina
   3. Comida rápida / fast food
   4. Casual / informal
   5. Temática / especializada (vegana, fusión, tapas, etc.)
   6. Otro (especificar): __________
Respuesta: _______

---

🧩 Sección 3 – Tecnologías Utilizadas Actualmente

1. ¿Tienes una página web? (elige una opción numérica):  
   1. Sí  
   2. No  
   Respuesta: _______

2. Si tienes web, ¿cuándo fue la última vez que se actualizó?  
   Ejemplo: “marzo 2024”, “más de un año”, “no aplica”  
   Respuesta: _______

3. ¿En qué plataforma está hecha tu web? (elige una opción numérica):
   1. WordPress
   2. Wix
   3. Shopify
   4. Squarespace
   5. Otra (especificar): __________
   6. No tiene web
   
+Respuesta: _______

4. ¿Utilizas algún sistema de facturación o gestión? (por ejemplo, para comandas, stock o caja)  
   Ejemplos: Revo, Last.app, Glop, Hosteltáctil, TpvStar, Oracle Micros, Otro (especificar), No usa ninguno  
   Respuesta: _______


5. ¿Qué canales usás para comunicarte con tus clientes? (elegí todas las que correspondan, separadas por coma):  
   1. Redes sociales (Instagram, Facebook, etc.)  
   2. WhatsApp  
   3. Email  
   4. Teléfono  
   5. Otro  
   Respuesta: _______

---

🧩 Sección 4 – Tu Web y Presencia Online

1. ¿Qué importancia le das actualmente a tu presencia online? (elige una opción numérica):  
   1. Muy importante – traemos muchos clientes desde internet  
   2. Algo importante – intentamos mejorar, pero no es prioridad  
   3. Poco importante – nos va bien con clientes de paso o recomendados  
   4. Nada importante – no creemos que nos aporte mucho  
   Respuesta: _______

2. ¿Tienes perfiles activos en redes sociales? (elige una opción numérica):  
   1. Sí, y publicamos regularmente  
   2. Sí, pero publicamos poco  
   3. No, pero queremos empezar  
   4. No usamos redes  
   Respuesta: _______

3. ¿Tu web aparece bien posicionada en Google al buscar tu nombre o tipo de negocio? (elige una opción numérica):  
   1. Sí, está bien posicionada  
   2. Más o menos, depende de la búsqueda  
   3. No aparece o está muy abajo  
   4. No lo sé  
   Respuesta: _______

4. ¿Qué herramientas usas actualmente para mejorar tu presencia online? (elegí todas las que correspondan, separadas por coma):  
   1. Redes sociales (Instagram, Facebook, etc.)  
   2. Ficha de Google Maps / Google My Business  
   3. SEO o posicionamiento web  
   4. Publicidad online (Google Ads, Meta Ads, etc.)  
   5. Otras  
   6. Ninguna  
   Respuesta: _______

5. ¿Qué funciones están disponibles y automatizadas en tu web? (elige todas las que correspondan, separadas por coma):  
   1. Reservas online  
   2. Pedidos para llevar o delivery  
   3. Contacto directo (chat, formularios)  
   4.Pago online  
   5. Otra: _______  
   Respuesta: _______


### 🧩 Sección 5 – Problemas y dolores del día a día en tu negocio

1. ¿En qué cosas sentís que se pierde más tiempo o se arma más lío? (podés elegir hasta 3)  
   - 1. Organizar las reservas y las mesas  
   - 2. Armar los turnos y coordinar al equipo  
   - 3. Atender a los clientes (pedidos, quejas, etc.)  
   - 4. Controlar el stock y pedir a los proveedores  
   - 5. Hacer facturas, cobrar y cerrar caja  
   - 6. Comunicarnos entre el equipo  
   - 7. Otra cosa: _______  

2. ¿Qué es lo que más te quita el sueño o te genera estrés? (elige solo 1)  
   - 1. Que no llegue plata o que no sea rentable  
   - 2. Tener que hacer mucho trabajo manual o tareas repetitivas  
   - 3. Que todo esté desorganizado o el equipo no funcione bien  
   - 4. Que no venga suficiente gente al local  
   - 5. Que el personal cambie mucho o sea difícil de formar  
   - 6. Otra cosa: _______

3. ¿Qué te gustaría poder hacer con menos esfuerzo y de forma automática?  
   (ejemplos: reservas, pedidos, compras, facturación, turnos, encuestas a clientes…)  
   Respuesta: _______  

4. ¿Hay algo que crees que debería ser más simple en tu trabajo, pero que ahora es complicado?  
   Respuesta: _______

5. ¿Qué tan útil creés que sería para vos que la tecnología te ayude a resolver estos problemas?  
   - 1. Mucho, me ahorraría un montón de tiempo y problemas  
   - 2. Bastante, aunque necesitaría ayuda para empezar  
   - 3. Poco, no me convence mucho lo digital  
   - 4. Nada, no lo veo necesario  


---

### 🧩 Sección 6 – ¿Y si algunas cosas se pudieran resolver con ayuda inteligente?

1. Si una herramienta te ayudara a hacer más fácil alguna tarea repetitiva o complicada, ¿cuál elegirías primero? (podés marcar hasta 2)  
   - 1. Armar los turnos del personal  
   - 2. Gestionar reservas automáticamente  
   - 3. Preparar pedidos para llevar  
   - 4. Recordar cuándo hay que comprar o reponer stock  
   - 5. Manejar quejas o comentarios de clientes  
   - 6. Detectar cuándo baja la cantidad de clientes  
   - 7. Otra: _______  

2. ¿Qué te parecería una herramienta que, según lo que pasa en tu local, te sugiera ideas para mejorar?  
   - 1. Muy buena, la usaría si es fácil  
   - 2. Suena bien, pero tendría que probarla  
   - 3. No me convence del todo  
   - 4. Prefiero manejarme a mi manera  

3. ¿Alguna vez usaste una herramienta con inteligencia artificial? (por ejemplo, ChatGPT)  
   - 1. Sí, la uso bastante  
   - 2. La probé alguna vez  
   - 3. Sé lo que es, pero no la usé  
   - 4. No tengo idea qué es  

4. ¿Qué te frena a probar herramientas nuevas en tu negocio?  
   Respuesta: _______

5. ¿Te gustaría recibir ideas para mejorar, pensadas según tu tipo de local y tus necesidades?  
   - 1. Sí, me interesa  
   - 2. Tal vez, si no me lleva mucho tiempo  
   - 3. No, prefiero seguir como estoy  




🧠 Sección Final – Observaciones del Encuestador (opcional)



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
