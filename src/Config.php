<?php

namespace Pressing;

class Config {
    /**
     * @var String
     */
    private static $default_filename = "default_config.json";

    /**
     * @var Array
     */
    private static $data;

    /**
     * @param String $overrides
     */
    public static function init($overrides = array()) {
        $default_file_dir = dirname(dirname(__FILE__));
        $default = json_decode(file_get_contents($default_file_dir . "/" . self::$default_filename), true);

        self::$data = $default + $overrides;

        self::add_extra_data();
    }

    private static function add_extra_data() {
        // extra things that aren't in config files
        self::$data['current_dir'] = getcwd();
    }

    /**
     * @param String $key
     *
     * @return mixed
     */
    public static function get($key) {
        if (!isset(self::$data[$key])) {
            throw new Exception("invalid config key");
        }

        return self::$data[$key];
    }
}