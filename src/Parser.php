<?php

namespace Pressing;

/**
 * methods to read/parse the file source
 */
class Parser {
    /**
     * how to find the frontmatter
     * @var RegEx
     */
    private static $frontmatter_regex = "/^---\n(.*)\n?---\n/";

    /**
     * @var string
     */
    private static $base;

    /**
     * parse out the frontmatter and content from the file
     *
     * @param String $path - absolute path
     *
     * @return Array - 'frontmatter' and 'content' values
     */
    public static function parse($path) {
        if (!file_exists($path)) {
            throw new \Exception("$path not found");
        }

        $src = file_get_contents($path);
        if (!$src) {
            throw new \Exception("could not read $path");
        }

        // first of all do we have any frontmatter in there?
        preg_match(self::$frontmatter_regex, $src, $matches);
        if (!empty($matches)) {
            // if we have an empty frontmatter, this will fail 
            // but we still want to act like there was something there
            if (!($frontmatter = json_decode(trim($matches[1]), true))) {
                $frontmatter = array();
            }
        } else {
            $frontmatter = false;
        }

        // remove any frontmatter
        $plain_src = preg_replace(self::$frontmatter_regex, "", $src);

        return array(
            'frontmatter' => $frontmatter,
            'content' => $plain_src
        );
    }
}