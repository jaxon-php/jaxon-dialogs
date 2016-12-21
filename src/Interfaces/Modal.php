<?php

namespace Jaxon\Dialogs\Interfaces;

Interface Modal
{
    public function modal($title, $content, array $buttons, array $options = array());

    public function show($title, $content, array $buttons, array $options = array());

    public function hide();
}
