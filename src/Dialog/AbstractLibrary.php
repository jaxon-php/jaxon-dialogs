<?php

/**
 * AbstractLibrary.php
 *
 * Base class for javascript dialog libraries.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog;

use Jaxon\App\Dialog\Library\LibraryInterface;

use function array_map;
use function rtrim;
use function Jaxon\Dialogs\dialog;

abstract class AbstractLibrary implements LibraryInterface
{
    /**
     * The dialog library helper
     *
     * @var LibraryHelper|null
     */
    private $xHelper = null;

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = [];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = [];

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
        return $this->xHelper ??= dialog()->getLibraryHelper($this->getName());
    }

    /**
     * Get the library base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return '';
    }

    /**
     * @param string $sFile The javascript file name
     *
     * @return string|null
     */
    private function getFileUrl(string $sFile): ?string
    {
        return rtrim($this->helper()->getBaseUrl(), '/') . "/$sFile";
    }

    /**
     * Get the CSS urls
     *
     * @return array
     */
    public function getCssUrls(): array
    {
        return array_map(fn($sFile) => $this->getFileUrl($sFile), $this->aCssFiles);
    }

    /**
     * Get the CSS header code
     *
     * @return string
     */
    public function getCssCode(): string
    {
        return '';
    }

     /**
     * Get the javascript files
     *
     * @return array
     */
    public function getJsUrls(): array
    {
        return array_map(fn($sFile) => $this->getFileUrl($sFile), $this->aJsFiles);
    }

    /**
     * Get the javascript code
     *
     * @return string
     */
    public function getJsCode(): string
    {
        return dialog()->renderLibraryScript($this->getName());
    }

    /**
     * Get the options of the js library
     *
     * @return array
     */
    public function getJsOptions(): array
    {
        return $this->helper()->getJsOptions();
    }
}
