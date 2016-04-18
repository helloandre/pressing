<?php

namespace Pressing;

class Pressing {
    /**
     * @var Pressing\Ingester
     */
    private $ingester;

    /**
     * @param String $config_file
     */
    public function __construct($config = array()) {
        // make sure global statics are set up
        Config::init($config);
        Template::init();

        $this->ingester = new Ingester();
    }

    public function generate() {
        // first we want to build a list of files we want to parse and/or copy over
        $this->ingester->build_file_list();

        // now we iterate over all files in our input_dir
        while ($file = $this->ingester->next_file()) {
            // do the damn thing
            $file->output();
        }
    }
}