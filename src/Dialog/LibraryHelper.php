<?php

namespace Jaxon\Dialogs\Dialog;

use Jaxon\Config\Config;

use function is_array;
use function rtrim;

class LibraryHelper
{
    /**
     * @var string
     */
    private string $sLibName;

    /**
     * @var string
     */
    private string $sBaseUrl;

    /**
     * @param AbstractLibrary $xDialogLibrary
     * @param Config $xConfig
     */
    public function __construct(private AbstractLibrary $xDialogLibrary, private Config $xConfig)
    {
        $this->sLibName = $this->xDialogLibrary->getName();
        $this->sBaseUrl = $this->xDialogLibrary->getBaseUrl();
    }

    /**
     * Get the value of a config option
     *
     * @param string $sOptionName The option name
     * @param mixed $xDefault The default value, to be returned if the option is not defined
     *
     * @return mixed
     */
    private function getOption(string $sOptionName, $xDefault = null): mixed
    {
        return $this->xConfig->getOption("{$this->sLibName}.$sOptionName", $xDefault);
    }

    /**
     * Check the presence of a config option
     *
     * @param string $sOptionName The option name
     *
     * @return bool
     */
    private function hasOption(string $sOptionName): bool
    {
        return $this->xConfig->hasOption("{$this->sLibName}.$sOptionName");
    }

    /**
     * Get the library base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $sBaseUrl = $this->hasOption('uri') ?
            $this->getOption('uri') :
            $this->xConfig->getOption('lib.uri', $this->sBaseUrl);
        if($this->hasOption('subdir'))
        {
            $sBaseUrl = rtrim($sBaseUrl, '/') . '/' . $this->getOption('subdir');
        }
        if($this->hasOption('version'))
        {
            $sBaseUrl = rtrim($sBaseUrl, '/') . '/' . $this->getOption('version');
        }
        return $sBaseUrl;
    }

    /**
     * Get the options of the js library
     *
     * @return array
     */
    public function getJsOptions(): array
    {
        $xOptions = $this->getOption('options', []);
        return is_array($xOptions) ? $xOptions : [];
    }
}
