<?php

namespace Jaxon\Dialogs\Dialog;

use Jaxon\Dialogs\DialogPlugin;

use function is_array;
use function rtrim;

class LibraryHelper
{
    /**
     * @param AbstractLibrary $xDialogLibrary
     * @param DialogPlugin $xDialogPlugin
     */
    public function __construct(private AbstractLibrary $xDialogLibrary,
        private DialogPlugin $xDialogPlugin)
    {}

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
        return $this->xDialogPlugin->config()
            ->getOption($this->xDialogLibrary->getName() . ".$sOptionName", $xDefault);
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
        return $this->xDialogPlugin->config()
            ->hasOption($this->xDialogLibrary->getName() . ".$sOptionName");
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
            $this->xDialogPlugin->config()->getOption('lib.uri',
                $this->xDialogLibrary->getBaseUrl());
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
