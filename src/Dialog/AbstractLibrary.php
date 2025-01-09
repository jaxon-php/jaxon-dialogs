<?php

/**
 * AbstractDialogLibrary.php
 *
 * Base class for javascript dialog libraries.
 *
 * @package jaxon-core
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog;

use function Jaxon\Dialogs\dialog;

abstract class AbstractLibrary
{
    /**
     * The dialog library helper
     *
     * @var LibraryHelper
     */
    private $xHelper = null;

    /**
     * Get the library name
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Get the helper
     *
     * @return LibraryHelper
     */
    public function helper(): LibraryHelper
    {
        return $this->xHelper ?: $this->xHelper = dialog()->getLibraryHelper($this->getName());
    }

    /**
     * Get the library base URI
     *
     * @return string
     */
    public function getUri(): string
    {
        return '';
    }

    /**
     * Get the CSS header code and file includes
     *
     * @return string
     */
    public function getJs(): string
    {
        return '';
    }

    /**
     * Get the javascript header code and file includes
     *
     * @return string
     */
    public function getCss(): string
    {
        return '';
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * @return string
     */
    public function getScript(): string
    {
        return '';
    }

    /**
     * Get the javascript code to be executed on page load
     *
     * @return string
     */
    public function getReadyScript(): string
    {
        return '';
    }
}
