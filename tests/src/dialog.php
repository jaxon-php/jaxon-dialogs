<?php

use Jaxon\App\CallableClass;
use Jaxon\App\Dialog\Library\LibraryInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;
use Jaxon\Dialogs\Dialog\AbstractLibrary;

class Dialog extends CallableClass
{
    public function success()
    {
        $this->response->dialog->title('Success')->success('This is a message!!');
    }

    public function info()
    {
        $this->response->dialog->title('Info')->info('This is a message!!');
    }

    public function warning()
    {
        $this->response->dialog->title('Warning')->warning('This is a message!!');
    }

    public function error()
    {
        $this->response->dialog->title('Error')->error('This is a message!!');
    }

    public function show()
    {
        $this->response->dialog->show('Dialog', 'This is the dialog content!!',
            [['title' => 'Save', 'class' => 'btn', 'click' => $this->rq()->save()->confirm('Save?')]]);
    }

    public function showWith()
    {
        $this->response->dialog->with('bootbox')->show('Dialog', 'This is the dialog content!!',
            [['title' => 'Save', 'class' => 'btn', 'click' => $this->rq()->save()->confirm('Save?')]]);
    }

    public function hide()
    {
        $this->response->dialog->hide();
    }
}

class TestDialogLibrary extends AbstractLibrary implements LibraryInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    const NAME = 'test';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}

interface TestInterface
{
    public function do();
}

class ClassWithInterface implements TestInterface
{
    public function do()
    {
        // Do something
    }
}
