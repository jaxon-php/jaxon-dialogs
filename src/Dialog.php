<?php

namespace Jaxon\Dialogs;

use Jaxon\Plugin\Response;

class Dialog extends Response
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new Libraries\Manager($this);
    }

    public function getName()
    {
        return 'dialogs';
    }

    public function generateHash()
    {
        // The version number is used as hash
        return '1.1.0b1';
    }

    public function getJs()
    {
        if(!$this->includeAssets())
        {
            return '';
        }
        $js = '';
        if(($modal = $this->manager->getModal()))
        {
            $js .= $modal->getJs() . "\n";
        }
        if(($alert = $this->manager->getAlert()))
        {
            $js .= $alert->getJs() . "\n";
        }
        if(($confirm = $this->manager->getConfirm()))
        {
            $js .= $confirm->getJs() . "\n";
        }
        return $js;
    }

    public function getCss()
    {
        if(!$this->includeAssets())
        {
            return '';
        }
        $css = '';
        if(($modal = $this->manager->getModal()))
        {
            $css .= $modal->getCss() . "\n";
        }
        if(($alert = $this->manager->getAlert()))
        {
            $css .= $alert->getCss() . "\n";
        }
        if(($confirm = $this->manager->getConfirm()))
        {
            $css .= $confirm->getCss() . "\n";
        }
        return $css;
    }

    public function getScript()
    {
        return '';
    }

    public function show($title, $content, $buttons, array $options = array())
    {
        $this->manager->getModal()->show($title, $content, $buttons, $options);
    }

    public function hide()
    {
        $this->manager->getModal()->hide();
    }

    public function success($message, $title = null)
    {
        $this->manager->getAlert()->success($message, $title);
    }

    public function info($message, $title = null)
    {
        $this->manager->getAlert()->info($message, $title);
    }

    public function warning($message, $title = null)
    {
        $this->manager->getAlert()->warning($message, $title);
    }

    public function error($message, $title = null)
    {
        $this->manager->getAlert()->error($message, $title);
    }
}
