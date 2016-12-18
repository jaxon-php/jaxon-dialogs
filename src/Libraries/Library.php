<?php

namespace Jaxon\Dialogs\Libraries\Bootbox;

use Jaxon\Dialogs\Interfaces\Plugin;

class Library implements Plugin
{
    protected $dialog = null;
    protected $name = null;

    final public function setDialog($dialog)
    {
        $this->dialog = $dialog;
    }

    final public function setName($name)
    {
        $this->name = $name;
    }

    final public function getName()
    {
        return $this->name;
    }

    public function getJs()
    {
        return '';
    }

    public function getCss()
    {
        return '';
    }

    public function getScript()
    {
        return '';
    }

    /**
     * Get the value of a config option
     *
     * @param string        $sName            The option name
     * @param mixed         $xDefault         The default value, to be returned if the option is not defined
     *
     * @return mixed        The option value, or its default value
     */
    final public function getOption($sName, $xDefault = null)
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->dialog->getOption($sName, $xDefault);
    }
    
    /**
     * Check the presence of a config option
     *
     * @param string        $sName            The option name
     *
     * @return bool        True if the option exists, and false if not
     */
    final public function hasOption($sName)
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->dialog->hasOption($sName);
    }
    
    /**
     * Get the names of the options matching a given prefix
     *
     * @param string        $sPrefix        The prefix to match
     *
     * @return array        The options matching the prefix
     */
    final public function getOptionNames($sPrefix)
    {
        $sPrefix = 'dialogs.' . $this->getName() . '.' . $sPrefix;
        $this->dialog->getOptionNames($sPrefix);
    }
}
