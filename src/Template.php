<?php

namespace Pressing;

/**
 * init our template engine and put a wrapper around rendering
 * to use the configured template engine
 */
class Template {

    private static $engine;

    public static function init() {
        $engine = Config::get('template_engine');
        $clazz = "Pressing\Templates\\$engine\\$engine";
        self::$engine = new $clazz;
    }

    public static function render($template, $data) {
        return self::$engine->render($template, $data);
    }
}