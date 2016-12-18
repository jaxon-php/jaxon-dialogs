<?php

namespace Jaxon\Dialogs\Interfaces;

Interface Alert
{
    public function success($message, $title = null);

    public function info($message, $title = null);

    public function warning($message, $title = null);

    public function error($message, $title = null);
}
