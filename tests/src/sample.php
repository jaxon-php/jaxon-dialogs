<?php

use function Jaxon\jaxon;

class SampleDialog
{
    public function myMethod()
    {
        $xResponse = jaxon()->getResponse();
        $xResponse->alert('This is a response!!');
    }
}
