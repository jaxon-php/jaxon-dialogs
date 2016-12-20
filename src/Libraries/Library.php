<?php

namespace Jaxon\Dialogs\Libraries;

use Jaxon\Dialogs\Interfaces\Plugin;

class Library implements Plugin
{
    protected $dialog = null;
    protected $name = null;

    /**
     * The object used to build the response that will be sent to the client browser
     *
     * @var \Jaxon\Response\Response
     */
    protected $xResponse;
    
    /**
     * Set the <Jaxon\Response\Response> object
     *
     * @param array         $xResponse            The response
     *
     * @return void
     */
    final public function setResponse($xResponse)
    {
        $this->xResponse = $xResponse;
    }
    
    /**
     * Get the <Jaxon\Response\Response> object
     *
     * @return object
     */
    final public function response()
    {
        return $this->xResponse;
    }
    
    /**
     * Add a client side plugin command to the response object
     *
     * @param array         $aAttributes        The attributes of the command
     * @param string        $sData                The data to be added to the command
     *
     * @return void
     */
    final public function addCommand($aAttributes, $sData)
    {
        $this->xResponse->addPluginCommand($this, $aAttributes, $sData);
    }

    final public function setDialog($dialog)
    {
        $this->dialog = $dialog;
        $this->setResponse($dialog->response());
    }

    final public function setName($name)
    {
        $this->name = $name;
    }

    final public function getName()
    {
        return $this->name;
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
        // The options names are relative to the plugin in Dialogs configuration 
        $sPrefix = 'dialogs.' . $this->getName() . '.' . $sPrefix;
        $nPrefixLength = strlen('dialogs.' . $this->getName() . '.');
        $aOptionNames = $this->dialog->getOptionNames($sPrefix);
        // Remove the prefix from the options names
        array_walk($aOptionNames, function(&$name) use ($nPrefixLength) {$name = substr($name, $nPrefixLength);});
        return $aOptionNames;
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
}
