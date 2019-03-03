<?php
/*
Plugin Name: Twenty Patcher for ClassicPress
Plugin URI: https://github.com/Vusys/twenty-patcher-for-classicpress
Description: Patches Twenty* Themes to rebrand the footer to ClassicPress
Author: Bryan Channon
Version: 0.0.1
Author URI: https://vuii.co.uk
*/

class TwentyPatcher
{
    private const WHITELIST = [
        'twentyten', 'twentyeleven', 'twentytwelve', 'twentythirteen', 'twentyfourteen',
        'twentyfifteen', 'twentysixteen', 'twentyseventeen', 'twentynineteen',
    ];

    private const REPLACEMENTS = [
        'https://wordpress.org/' => 'https://www.classicpress.net/',
        'WordPress'              => 'ClassicPress',
    ];

    public function __construct()
    {
        add_action('switch_theme', [$this, 'patch']);
        add_action('upgrader_process_complete', [$this, 'patch']);
        register_activation_hook(__FILE__, [$this, 'patch']);
    }

    public function patch()
    {
        if (!in_array(get_option('stylesheet'), self::WHITELIST, true)) {
            return;
        }

        $theme = wp_get_theme(get_option('stylesheet'));

        if (get_option('stylesheet') === 'twentyseventeen') {
            $file = $theme->get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'template-parts' . DIRECTORY_SEPARATOR . 'footer' . DIRECTORY_SEPARATOR . 'site-info.php';
        } else {
            $file = $theme->get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'footer.php';
        }

        if (file_exists($file)) {
            $content = file_get_contents($file);
            $content = str_replace(array_keys(self::REPLACEMENTS), array_values(self::REPLACEMENTS), $content);
            file_put_contents($file, $content);
        }
    }
}

new TwentyPatcher;
