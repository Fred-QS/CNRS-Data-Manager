<?php

namespace CnrsDataManager\Core\Controllers;

use CnrsDataManager\Core\Interfaces\TemplateLoaderInterface;
use CnrsDataManager\Core\Interfaces\PageInterface;

class TemplateLoader implements TemplateLoaderInterface {

    public function init( PageInterface $page ) {
        $this->templates = wp_parse_args(
            array( 'page.php', 'index.php' ), (array) $page->getTemplate()
        );
    }

    public function load() {
        do_action( 'template_redirect' );
        $template = $this->locate_template( array_filter( $this->templates ) );
        $filtered = apply_filters( 'template_include',
            apply_filters('virtual_page_template', $template)
        );
        if ( empty( $filtered ) || file_exists( $filtered ) ) {
            $template = $filtered;
        }
        if ( ! empty( $template ) && file_exists( $template ) ) {
            require_once $template;
        }
    }

    private function locate_template($template_names, $load = false, $load_once = true, $args = array())
    {
        wp_set_template_globals();

        $located = '';
        foreach ((array) $template_names as $template_name) {
            if (!$template_name) {
                continue;
            }
            $template = CNRS_DATA_MANAGER_PATH . '/templates/includes/' . $template_name;
            if ( file_exists($template)) {
                $located = $template;
                break;
            }
        }

        if ( $load && '' !== $located ) {
            load_template( $located, $load_once, $args );
        }

        return $located;
    }
}