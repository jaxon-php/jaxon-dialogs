<?php

namespace Jaxon\Dialogs\Dialog;

use Jaxon\App\Config\ConfigManager;
use Jaxon\App\Dialog\Library\LibraryInterface;
use Jaxon\Utils\Template\TemplateEngine;

use function is_array;
use function rtrim;
use function trim;

class LibraryHelper
{
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
        $sDefaultUri = $xConfigManager->getAppOption('dialogs.lib.uri', $xDialogLibrary->getUri());
        // Set the library URI.
        $this->sUri = rtrim($this->getOption('uri', $sDefaultUri), '/');
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
        return $this->xConfigManager->getAppOption($sOptionName, $xDefault);
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
        return $this->xConfigManager->hasAppOption("dialogs.{$this->sName}.$sOptionName");
    }

    /**
     * Get the names of the options matching a given prefix
     *
     * @param string $sPrefix The prefix to match
     *
     * @return array
     */
    public function getOptionNames(string $sPrefix): array
    {
        // The options names are relative to the plugin in Dialogs configuration
        return $this->xConfigManager->getOptionNames("dialogs.{$this->sName}.$sPrefix");
    }

    /**
     * Get the options of the js library
     *
     * @return array
     */
    public function getJsOptions(): array
    {
        $xOptions = $this->xConfigManager->getAppOption("dialogs.{$this->sName}.options", []);
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
     * Get the javascript HTML header code
     *
     * @param string $sFile The javascript file name
     *
     * @return string
     */
    public function getJsCode(string $sFile): string
    {
        // If this 'assets.js' option is defined and evaluates to false, then the asset is not displayed.
        $sUri = $this->getAssetUri('assets.js', $sFile);
        return !$sUri ? '' : '<script type="text/javascript" src="' . $sUri . '"></script>';
    }

    /**
     * Get the CSS HTML header code
     *
     * @param string $sFile The CSS file name
     *
     * @return string
     */
    public function getCssCode(string $sFile): string
    {
        // If this 'assets.css' option is defined and evaluates to false, then the asset is not displayed.
        $sUri = $this->getAssetUri('assets.css', $sFile);
        return !$sUri ? '' : '<link rel="stylesheet" href="' . $sUri . '" />';
    }

    /**
     * Render a template
     *
     * @param string $sTemplate The name of template to be rendered
     * @param array $aVars The template vars
     *
     * @return string
     */
    public function render(string $sTemplate, array $aVars = []): string
    {
        return $this->xTemplateEngine->render("jaxon::dialogs::$sTemplate", $aVars);
    }
}
