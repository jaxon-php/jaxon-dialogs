<?php

namespace Jaxon\Dialogs\Dialog;

use Jaxon\App\Config\ConfigManager;
use Jaxon\App\Config\ConfigTrait;
use Jaxon\App\Dialog\Library\LibraryInterface;
use Jaxon\Utils\Template\TemplateEngine;

use function array_filter;
use function array_map;
use function implode;
use function is_array;
use function rtrim;
use function trim;

class LibraryHelper
{
    use ConfigTrait;

    /**
     * Default library URL
     *
     * @var string
     */
    const JS_LIB_URL = 'https://cdn.jsdelivr.net/gh/jaxon-php/jaxon-dialogs@main/js';

    /**
     * The name of the library
     *
     * @var string
     */
    protected $sName = '';

    /**
     * The URI where to get the library files from
     *
     * @var string
     */
    protected $sUri = '';

    /**
     * The constructor
     *
     * @param LibraryInterface $xDialogLibrary
     * @param ConfigManager $xConfigManager
     * @param TemplateEngine $xTemplateEngine
     */
    public function __construct(LibraryInterface $xDialogLibrary,
        private ConfigManager $xConfigManager, private TemplateEngine $xTemplateEngine)
    {
        // Set the library name
        $this->sName = $xDialogLibrary->getName();
        // Set the default URI.
        $sDefaultUri = $this->getAppOption('dialogs.lib.uri', $xDialogLibrary->getUri());
        // Set the library URI.
        $this->sUri = rtrim($this->getOption('uri', $sDefaultUri), '/');
    }

    /**
     * @return ConfigManager
     */
    protected function config(): ConfigManager
    {
        return $this->xConfigManager;
    }

    /**
     * Get the value of a config option
     *
     * @param string $sOptionName The option name
     * @param mixed $xDefault The default value, to be returned if the option is not defined
     *
     * @return mixed
     */
    public function getOption(string $sOptionName, $xDefault = null)
    {
        $sOptionName = "dialogs.{$this->sName}.$sOptionName";
        return $this->getAppOption($sOptionName, $xDefault);
    }

    /**
     * Check the presence of a config option
     *
     * @param string $sOptionName The option name
     *
     * @return bool
     */
    public function hasOption(string $sOptionName): bool
    {
        return $this->hasAppOption("dialogs.{$this->sName}.$sOptionName");
    }

    /**
     * Get the options of the js library
     *
     * @return array
     */
    public function getJsOptions(): array
    {
        $xOptions = $this->getAppOption("dialogs.{$this->sName}.options", []);
        return is_array($xOptions) ? $xOptions : [];
    }

    /**
     * @param string $sOption The assets option name
     * @param string $sFile The javascript file name
     *
     * @return string|null
     */
    private function getAssetUri(string $sOption, string $sFile): ?string
    {
        return !$this->hasOption($sOption) ? "{$this->sUri}/$sFile" :
            (trim($this->getOption($sOption)) ?: null);
    }

    /**
     * @param string $sOption The assets option name
     * @param array $aFiles The asset files
     *
     * @return array
     */
    private function getUris(string $sOption, array $aFiles): array
    {
        $aFiles = array_map(fn($sFile) =>
            $this->getAssetUri($sOption, $sFile), $aFiles);
        return array_filter($aFiles, fn($sFile) => $sFile !== null);
    }

    /**
     * @param string $sUri
     *
     * @return string
     */
    private function getJsTag(string $sUri): string
    {
        return '<script type="text/javascript" src="' . $sUri . '"></script>';
    }

    /**
     * Get the javascript HTML header code
     *
     * @param array $aFiles The js files
     *
     * @return string
     */
    public function getJs(array $aFiles): string
    {
        $aFiles = $this->getUris('assets.js', $aFiles);
        $aFiles = array_map(fn($sUri) => $this->getJsTag($sUri), $aFiles);
        return implode("\n", $aFiles);
    }

    /**
     * Get the javascript HTML header code
     *
     * @param string $sFile The javascript file name
     *
     * @return string
     */
    public function getJsHtml(string $sFile): string
    {
        // If this 'assets.js' option is defined and evaluates to false, then the asset is not displayed.
        $sUri = $this->getAssetUri('assets.js', $sFile);
        return !$sUri ? '' : $this->getJsTag($sUri);
    }

    /**
     * @param string $sUri
     *
     * @return string
     */
    private function getCssTag(string $sUri): string
    {
        return '<link rel="stylesheet" href="' . $sUri . '" />';
    }

    /**
     * Get the CSS HTML header code
     *
     * @param array $aFiles The CSS files
     *
     * @return string
     */
    public function getCss(array $aFiles): string
    {
        $aFiles = $this->getUris('assets.css', $aFiles);
        $aFiles = array_map(fn($sUri) => $this->getCssTag($sUri), $aFiles);
        return implode("\n", $aFiles);
    }

    /**
     * Get the CSS HTML header code
     *
     * @param string $sFile The CSS file name
     *
     * @return string
     */
    public function getCssHtml(string $sFile): string
    {
        // If this 'assets.css' option is defined and evaluates to false, then the asset is not displayed.
        $sUri = $this->getAssetUri('assets.css', $sFile);
        return !$sUri ? '' : $this->getCssTag($sUri);
    }

    /**
     * Get the library script code
     *
     * @return string
     */

    public function getScript(): string
    {
        return !$this->getLibOption('js.app.export', false) ?
            $this->xTemplateEngine->render("jaxon::dialogs::{$this->sName}.js") : '';
    }

    /**
     * Get the javascript HTML header code
     *
     * @return array
     */
    public function getFiles(): array
    {
        if(!$this->getLibOption('js.app.export', false))
        {
            return [];
        }

        $sJsFileName = !$this->getLibOption('js.app.minify', false) ?
            "{$this->sName}.js" : "{$this->sName}.min.js";
        return [self::JS_LIB_URL . "/$sJsFileName"];
    }
}
