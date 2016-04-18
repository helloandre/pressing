<?php

namespace Pressing\Templates\Twig;

class Loader extends \Twig_Loader_Array {
    /**
     * We always want to re-render our template, as we 
     * continuously replace it for memory-saving reasons
     */
    public function isFresh($name, $ts) {
        return false;
    }
}