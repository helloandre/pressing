<?php

namespace Pressing\Templates\Twig;

/**
 * This is a lightly-abused version of Twig as we trick
 * it into only keeping one template renderable at a time
 * so that we don't load all templates into memory.
 * we don't take a performance hit by forcing it to do this
 * because we would be re-rendering these anyway
 */
class Twig {
    /**
     * @var Twig_Environment
     */
    private $env;

    /**
     * @var \Pressing\Templates\Twig\Loader
     */
    private $loader;

    /**
     * the name of the template we tell twig to render over and over
     * @var String
     */
    private $template_name = 'current_template';

    /**
     * @param String $config_file
     */
    public function __construct() {
        $templates = \Pressing\Config::get('template_dir');

        // first we need our template loader based on the filesystem so we can
        // reference them in the templates that we're going to allow users to build
        $template_loader = new \Twig_Loader_Filesystem($templates);

        // our loader to render any requested template
        $this->loader = new Loader(array());

        $this->env = new \Twig_Environment(new \Twig_Loader_Chain(array($template_loader, $this->loader)));
    }

    public function render($content, $data) {
        // if there is a template set in the data, we need to inject a little bit of "helper" code
        // so that twig knows to extend from that template
        // (but the user doesn't *have* to explicitly do this themselves)
        if (isset($data['template'])) {
            $content = "{% extends '" . $data['template'] . "' %}\n" . $content;
        }

        // set the teamplte's content so that twig will render this one this time
        $this->loader->setTemplate($this->template_name, $content);

        // now trick it into re-rendering the "same" template again
        // as isFresh is always false
        return $this->env->render($this->template_name, $data);
    }
}