<?php

/**
 * Plugin.php - Interface for javascript library adapters.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Contracts;

interface Plugin
{
    /**
     * Get the plugin name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the CSS header code and file includes
     *
     * @return string
     */
    public function getCss();

    /**
     * Get the javascript header code and file includes
     *
     * @return string
     */
    public function getJs();

    /**
     * Get the javascript code to be printed into the page
     *
     * @return string
     */
    public function getScript();

    /**
     * Get the javascript code to be executed on page load
     *
     * @return string
     */
    public function getReadyScript();
}
