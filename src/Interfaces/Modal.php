<?php

namespace Jaxon\Dialogs\Interfaces;

Interface Modal
{
    public function show($title, $content, array $buttons, array $options = array());

    public function hide();
}
