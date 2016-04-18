<?php

namespace Pressing;

class File {
    private $should_ignore_extensions = array(
        'jpg',
        'jpeg',
        'gif',
        'png',
        'css',
        'js',
        'map',
        'eot',
        'svg',
        'ttf',
        'woff',
        'woff2'
    );

    /**
     * @var String
     */
    private $relative_path;

    /**
     * @var String
     */
    private $absolute_path;

    /**
     * @var String
     */
    private $output_path;

    /**
     * @param String $data - should only contain 'relative_path'
     */
    public function __construct($data) {
        $this->relative_path = $data['path'];

        $input_base = trim(Config::get('input_dir'), '/');
        $this->input_path = $input_base . '/' . trim($this->relative_path);

        $output_base = trim(Config::get('output_dir'), '/');
        $this->output_path = $output_base . '/' . trim($this->relative_path);
    }

    /**
     * run this file through our templating engine
     */
    private function render() {
        $parsed_data = Parser::parse($this->input_path);

        if ($parsed_data['frontmatter'] !== false && !$this->should_ignore()) {
            return Template::render($parsed_data['content'], $parsed_data['frontmatter']);
        }

        return $parsed_data['content'];
    }

     /**
     * this file now has it's contents and it parsed
     * we just need to put it where it needs to go
     */
    public function output() {
        $parts = explode('/', $this->output_path);
        // get rid of the filename
        array_pop($parts);

        $path = implode('/', $parts);
        if ($path && !file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \Exception("could not make directory $path");
            }
        }

        if (!file_put_contents($this->output_path, $this->render())) {
            throw new \Exception("could not write $filename");
        }
    }

    /**
     * and it should be run through our template renderer
     *
     * @return bool
     */
    private function should_ignore() {
        $parts = explode('.', $this->relative_path);
        $ext = strtolower(end($parts));
        return in_array($ext, $this->should_ignore_extensions);
    }
}