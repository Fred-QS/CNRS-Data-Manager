<?php

namespace CnrsDataManager\Core\Interfaces;

interface TemplateLoaderInterface {

    /**
     * Setup loader for a page objects
     *
     * @param \CnrsDataManager\Core\Interfaces\PageInterface $page matched virtual page
     */
    public function init( PageInterface $page );

    /**
     * Trigger core and custom hooks to filter templates,
     * then load the found template.
     */
    public function load();
}