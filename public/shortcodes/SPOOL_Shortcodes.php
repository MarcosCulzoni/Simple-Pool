<?php
namespace AI_Conector\Public\Shortcodes;

if (!defined('ABSPATH')) {
    exit;
}


// ------------------------Clase principal para la inicializacion de todos los shortCodes
class AI_ConectorShortcodes
{
    public static function init()
    {
        AI_ConectorSettingsShortcodes::init();
        AI_ConectorA_MenuShortcodes::init();
        AI_ConectorSettings::init();
    }
}
