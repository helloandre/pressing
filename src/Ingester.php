<?php

namespace Pressing;

class Ingester {
    /**
     * all paths and some metadata about them
     * @var Array
     */
    private $paths;

    /**
     * after we've scanned our input_dir this is now many
     * files we're going to have to copy/parse
     * @var integer
     */
    private $total_paths;

    public function build_file_list() {
        $dir = $this->absolute(Config::get('input_dir'));

        if (!file_exists($dir)) {
            throw new \Exception("cannot find $dir");
        }

        if (!is_dir($dir)) {
            throw new \Exception("$dir is not a directory");
        }

        $this->scandir(Config::get('input_dir'));
        $this->total_paths = count($this->paths);
    }

    /**
     * the next file to be processed
     * this is a memory-efficient way to process all the files we need to
     *
     * @return \Pressing\File
     */
    public function next_file() {
        if (empty($this->paths)) {
            return false;
        }

        return new File(array_pop($this->paths));
    }

    /**
     * are we there yet?
     *
     * @return float
     */
    public function percent_complete() {
        return (1 - round((count($this->paths) / $this->total_paths), 2)) * 100;
    }

    /**
     * recursively scan the directory for any files
     *
     * @param String $dir - relative to getcwd()
     */
    private function scandir($dir) {
        $dir = trim($dir, '/') . '/';
        $absolute = $this->absolute($dir);
        $files = scandir($absolute);

        foreach ($files as $file) {
            // ignore relative references
            if ($file === "." || $file === "..") {
                continue;
            }

            if (is_dir($absolute . $file)) {
                $this->scandir($dir . $file);
            } else {
                // we want the relative path inside the input_dir so we can
                // put the file in the same place in the output_dir
                $path = preg_replace("#^" . Config::get('input_dir') .  "#", "", $dir . $file);
                $this->paths[] = array(
                    'path' => $path
                );
            }
        }
    }

    /**
     * sanitize and concatinate a base and trailing dir
     *
     * @param String $trail
     *
     * @return String - will always contain a trailing slash
     */
    private function absolute($trail) {
        return '/' . trim(Config::get('current_dir'), '/') . '/' . trim($trail, '/') . '/';
    }
}