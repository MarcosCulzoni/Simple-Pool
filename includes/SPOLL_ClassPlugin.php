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




        

---

🧩 Sección 1 – Presentación y Objetivo de la Encuesta

Ayúdanos a entender los desafíos que enfrentan bares y restaurantes en verano para crear 
soluciones útiles, incluidas herramientas con IA. La encuesta es breve, anónima y nos permitirá
 diseñar mejoras que ahorren tiempo y dinero.

---

🧩 Sección 2 – Información Básica del Negocio

1. Tipo de negocio (elige una opción):  
   A. Restaurante  
   B. Bar  
   C. Cafetería  
   D. Food Truck  
   E. Panadería / Pastelería  
   F. Otro  
   Respuesta: _______

2. Ciudad:  
   Respuesta: _______

3. Zona (elige una opción):  
   A. Casco antiguo / Centro histórico  
   B. Zona residencial  
   C. Zona turística o de alto tránsito  
   D. Zona costera (paseo marítimo / playa)  
   E. Otra (especificar): __________  
   Respuesta: _______

4. Tamaño del local (número aproximado de mesas):  
   A. De 1 a 5 mesas  
   B. De 6 a 10 mesas  
   C. De 11 a 20 mesas  
   D. De 21 a 40 mesas  
   E. Más de 40  
   Respuesta: _______

5. Público objetivo (elige una opción):  
   A. Predominan turistas  
   B. Predominan locales  
   C. 50% turistas / 50% locales  
   Respuesta: _______

6. Tipo de experiencia gastronómica (elige una opción):  
   A. Familiar / tradicional  
   B. Gourmet / alta cocina  
   C. Comida rápida / fast food  
   D. Casual / informal  
   E. Temática / especializada (vegana, fusión, tapas, etc.)  
   F. Otro (especificar): __________  
   Respuesta: _______

---

🧩 Sección 3 – Tecnologías Utilizadas Actualmente

1. ¿Tienes una página web? (elige una opción):  
   A. Sí  
   B. No  
   Respuesta: _______

2. Si tienes web, ¿cuándo fue la última vez que se actualizó?  
   Ejemplo: “marzo 2024”, “más de un año”, “no aplica”  
   Respuesta: _______

3. ¿En qué plataforma está hecha tu web? (elige una opción):  
   A. WordPress  
   B. Wix  
   C. Shopify  
   D. Squarespace  
   E. Otra (especificar): __________  
   F. No tiene web  
   Respuesta: _______

4. ¿Utilizas algún sistema de facturación o gestión?  
   (por ejemplo, para comandas, stock o caja)  
   Ejemplos: Revo, Last.app, Glop, Hosteltáctil, TpvStar, Oracle Micros, Otro (especificar), No usa ninguno  
   Respuesta: _______

5. ¿Qué canales usás para comunicarte con tus clientes?  
   (elegí todas las que correspondan, separadas por coma):  
   A. Redes sociales (Instagram, Facebook, etc.)  
   B. WhatsApp  
   C. Email  
   D. Teléfono  
   E. Otro  
   Respuesta: _______

---

🧩 Sección 4 – Tu Web y Presencia Online

1. ¿Qué importancia le das actualmente a tu presencia online? (elige una opción):  
   A. Muy importante – traemos muchos clientes desde internet  
   B. Algo importante – intentamos mejorar, pero no es prioridad  
   C. Poco importante – nos va bien con clientes de paso o recomendados  
   D. Nada importante – no creemos que nos aporte mucho  
   Respuesta: _______

2. ¿Tienes perfiles activos en redes sociales? (elige una opción):  
   A. Sí, y publicamos regularmente  
   B. Sí, pero publicamos poco  
   C. No, pero queremos empezar  
   D. No usamos redes  
   Respuesta: _______

3. ¿Tu web aparece bien posicionada en Google al buscar tu nombre o tipo de negocio? (elige una opción):  
   A. Sí, está bien posicionada  
   B. Más o menos, depende de la búsqueda  
   C. No aparece o está muy abajo  
   D. No lo sé  
   Respuesta: _______

4. ¿Qué herramientas usas actualmente para mejorar tu presencia online?  
   (elegí todas las que correspondan, separadas por coma):  
   A. Redes sociales (Instagram, Facebook, etc.)  
   B. Ficha de Google Maps / Google My Business  
   C. SEO o posicionamiento web  
   D. Publicidad online (Google Ads, Meta Ads, etc.)  
   E. Otras  
   F. Ninguna  
   Respuesta: _______

5. ¿Qué funciones están disponibles y automatizadas en tu web?  
   (elige todas las que correspondan, separadas por coma):  
   A. Reservas online  
   B. Pedidos para llevar o delivery  
   C. Contacto directo (chat, formularios)  
   D. Pago online  
   E. Otra: _______  
   Respuesta: _______

---

### 🧩 Sección 5 – Problemas y dolores del día a día en tu negocio

1. ¿En qué cosas sentís que se pierde más tiempo o se arma más lío? (podés elegir hasta 3)  
   A. Organizar las reservas y las mesas  
   B. Armar los turnos y coordinar al equipo  
   C. Atender a los clientes (pedidos, quejas, etc.)  
   D. Controlar el stock y pedir a los proveedores  
   E. Hacer facturas, cobrar y cerrar caja  
   F. Comunicarnos entre el equipo  
   G. Otra cosa: _______  

2. ¿Qué es lo que más te quita el sueño o te genera estrés? (elige solo 1)  
   A. Que no llegue plata o que no sea rentable  
   B. Tener que hacer mucho trabajo manual o tareas repetitivas  
   C. Que todo esté desorganizado o el equipo no funcione bien  
   D. Que no venga suficiente gente al local  
   E. Que el personal cambie mucho o sea difícil de formar  
   F. Otra cosa: _______

3. ¿Qué te gustaría poder hacer con menos esfuerzo y de forma automática?  
   (ejemplos: reservas, pedidos, compras, facturación, turnos, encuestas a clientes…)  
   Respuesta: _______  

4. ¿Hay algo que crees que debería ser más simple en tu trabajo, pero que ahora es complicado?  
   Respuesta: _______

5. ¿Qué tan útil creés que sería para vos que la tecnología te ayude a resolver estos problemas?  
   A. Mucho, me ahorraría un montón de tiempo y problemas  
   B. Bastante, aunque necesitaría ayuda para empezar  
   C. Poco, no me convence mucho lo digital  
   D. Nada, no lo veo necesario  

6. En verano o temporada alta, ¿qué problemas de los que mencionaste suelen volverse más difíciles o frecuentes?  
   Respuesta: _______________________________

---

### 🧩 Sección 6 – ¿Y si algunas cosas se pudieran resolver con ayuda inteligente?

1. Si una herramienta te ayudara a hacer más fácil alguna tarea repetitiva o complicada, ¿cuál elegirías primero? (podés marcar hasta 2)  
   A. Armar los turnos del personal  
   B. Gestionar reservas automáticamente  
   C. Preparar pedidos para llevar  
   D. Recordar cuándo hay que comprar o reponer stock  
   E. Manejar quejas o comentarios de clientes  
   F. Detectar cuándo baja la cantidad de clientes  
   G. Otra: _______  

2. ¿Qué te parecería una herramienta que, según lo que pasa en tu local, te sugiera ideas para mejorar?  
   A. Muy buena, la usaría si es fácil  
   B. Suena bien, pero tendría que probarla  
   C. No me convence del todo  
   D. Prefiero manejarme a mi manera  

3. ¿Alguna vez usaste una herramienta con inteligencia artificial? (por ejemplo, ChatGPT)  
   A. Sí, la uso bastante  
   B. La probé alguna vez  
   C. Sé lo que es, pero no la usé  
   D. No tengo idea qué es  

4. ¿Qué te frena a probar herramientas nuevas en tu negocio?  
   Respuesta: _______

5. ¿Te gustaría recibir ideas para mejorar, pensadas según tu tipo de local y tus necesidades?  
   A. Sí, me interesa  
   B. Tal vez, si no me lleva mucho tiempo  
   C. No, prefiero seguir como estoy  

---

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
