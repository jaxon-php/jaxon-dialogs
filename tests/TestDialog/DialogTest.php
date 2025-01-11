<?php

namespace Jaxon\Dialogs\Tests\TestDialog;

require_once __DIR__ . '/../src/dialog.php';

use Jaxon\Jaxon;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Dialogs\Dialog\Library\Bootbox;
use Jaxon\Dialogs\Dialog\Library\Bootstrap;
use Jaxon\Dialogs\Dialog\Library\CuteAlert;
use Jaxon\Exception\RequestException;
use Jaxon\Exception\SetupException;
use Jaxon\Utils\Http\UriException;
use Nyholm\Psr7Server\ServerRequestCreator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use Dialog;
use TestDialogLibrary;

use function get_class;
use function Jaxon\jaxon;
use function Jaxon\rq;
use function Jaxon\pm;
use function Jaxon\Dialogs\dialog;
use function Jaxon\Dialogs\_register;

class DialogTest extends TestCase
{
    /**
     * @throws SetupException
     */
    public function setUp(): void
    {
        _register();
        jaxon()->setOption('core.prefix.class', '');
        jaxon()->setOption('core.request.uri', 'http://example.test/path');
        jaxon()->register(Jaxon::CALLABLE_CLASS, Dialog::class);
        dialog()->registerLibrary(TestDialogLibrary::class, TestDialogLibrary::NAME);
    }

    /**
     * @throws SetupException
     */
    public function tearDown(): void
    {
        jaxon()->reset();
        parent::tearDown();
    }

    public function testDialogSettings()
    {
        $this->assertEquals('', dialog()->getConfirmLibrary()->getName());
        $this->assertEquals(Alert::class, get_class(dialog()->getConfirmLibrary()));
        $this->assertEquals(Alert::class, get_class(dialog()->getAlertLibrary()));
        $this->assertEquals(null, dialog()->getModalLibrary());

        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        $this->assertEquals(Bootstrap::class, get_class(dialog()->getConfirmLibrary()));
        $this->assertEquals(Bootstrap::class, get_class(dialog()->getAlertLibrary()));
        $this->assertEquals(Bootstrap::class, get_class(dialog()->getModalLibrary()));

        jaxon()->app()->setOption('dialogs.default.modal', 'bootbox');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootbox');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootbox');
        $this->assertEquals(Bootbox::class, get_class(dialog()->getConfirmLibrary()));
        $this->assertEquals(Bootbox::class, get_class(dialog()->getAlertLibrary()));
        $this->assertEquals(Bootbox::class, get_class(dialog()->getModalLibrary()));
    }

    public function testDialogOptions()
    {
        jaxon()->app()->setOption('dialogs.default.alert', 'cute');
        $xAlertLibrary = dialog()->getAlertLibrary();
        $this->assertEquals(CuteAlert::class, get_class($xAlertLibrary));
    }

    public function testDialogDefaultMethods()
    {
        jaxon()->app()->setOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $xConfirmLibrary = dialog()->getConfirmLibrary();
        $this->assertEquals('', $xConfirmLibrary->getUri());
        $this->assertEquals('', $xConfirmLibrary->getJs());
        $this->assertEquals('', $xConfirmLibrary->getScript());
        $this->assertEquals('', $xConfirmLibrary->getReadyScript());

        $xDialogPlugin = jaxon()->di()->getDialogPlugin();
    }

    public function testDialogJsCode()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['bootbox', 'bootstrap', 'cute']);
        $sJsCode = jaxon()->js();
        $this->assertStringContainsString('bootbox.min.js', $sJsCode);
        $this->assertStringContainsString('bootstrap-dialog.min.js', $sJsCode);
        $this->assertStringContainsString('cute-alert.js', $sJsCode);
    }

    public function testDialogCssCode()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['bootstrap', 'cute']);
        $sCssCode = jaxon()->css();
        $this->assertStringContainsString('bootstrap-dialog.min.css', $sCssCode);
        $this->assertStringContainsString('cute-alert/style.css', $sCssCode);
    }

    /**
     * @throws UriException
     */
    public function testDialogScriptCode()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        jaxon()->app()->setOption('dialogs.lib.use', ['bootbox', 'cute', 'jalert']);

        $sScriptCode = jaxon()->getScript();
        $this->assertStringContainsString("jaxon.dialog.register", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('bootstrap'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('bootbox'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('cute'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('jalert'", $sScriptCode);
    }

    /**
     * @throws RequestException
     */
    public function testDefaultDialogSuccess()
    {
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'success',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibrarySuccess()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'success',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws RequestException
     */
    public function testDefaultDialogWarning()
    {
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'warning',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryWarning()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'warning',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws RequestException
     */
    public function testDefaultDialogInfo()
    {
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'info',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryInfo()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'info',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws RequestException
     */
    public function testDefaultDialogError()
    {
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'error',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryError()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
            ->fromGlobals()
            ->withQueryParams([
                'jxncall' => json_encode([
                    'type' => 'class',
                    'name' => 'Dialog',
                    'method' => 'error',
                    'args' => [],
                ]),
            ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.alert.show', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryShow()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'show',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.modal.show', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws RequestException
     */
    public function testBootboxLibraryShow()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootbox');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootbox');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootbox');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'show',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.modal.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryShowWith()
    {
        // Choose the bootstrap library in the options, and use the bootbox in the class.
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'showWith',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.modal.show', $aCommands[0]['name']);
    }

    /**
     * @throws RequestException
     */
    public function testDialogLibraryHide()
    {
        jaxon()->app()->setOption('dialogs.default.modal', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.alert', 'bootstrap');
        jaxon()->app()->setOption('dialogs.default.confirm', 'bootstrap');
        // The server request
        jaxon()->di()->set(ServerRequestInterface::class, function($c) {
            return $c->g(ServerRequestCreator::class)
                ->fromGlobals()
                ->withQueryParams([
                    'jxncall' => json_encode([
                        'type' => 'class',
                        'name' => 'Dialog',
                        'method' => 'hide',
                        'args' => [],
                    ]),
                ]);
        });

        $this->assertTrue(jaxon()->di()->getRequestHandler()->canProcessRequest());
        jaxon()->di()->getRequestHandler()->processRequest();

        $aCommands = jaxon()->getResponse()->getCommands();
        $this->assertCount(1, $aCommands);
        $this->assertEquals('dialog.modal.hide', $aCommands[0]['name']);
        $this->assertEquals('dialog', $aCommands[0]['options']['plugin']);
    }

    /**
     * @throws SetupException
     */
    public function testConfirmMessageSuccess()
    {
        jaxon()->register(Jaxon::CALLABLE_CLASS, 'Sample', __DIR__ . '/../src/sample.php');
        jaxon()->app()->setOption('dialogs.default.alert', 'cute');
        jaxon()->app()->setOption('dialogs.default.confirm', 'noty');
        $this->assertEquals(
            'jaxon.exec({"_type":"expr","calls":[{"_type":"func","_name":"Sample.method",' .
                '"args":[{"_type":"html","_name":"elt_id"}]}],' .
                '"confirm":{"lib":"noty","question":{"title":"","phrase":{"str":"Really?","args":[]}}},' .
                '"alert":{"lib":"cute","message":{"type":"success","title":"","phrase":{"str":"No confirm","args":[]}}}})',
            rq('Sample')->method(pm()->html('elt_id'))->confirm("Really?")
                ->elseSuccess("No confirm")->__toString()
        );
    }

    /**
     * @throws SetupException
     */
    public function testConfirmMessageInfo()
    {
        jaxon()->register(Jaxon::CALLABLE_CLASS, 'Sample', __DIR__ . '/../src/sample.php');
        jaxon()->app()->setOption('dialogs.default.alert', 'cute');
        jaxon()->app()->setOption('dialogs.default.confirm', 'noty');
        $this->assertEquals(
            'jaxon.exec({"_type":"expr","calls":[{"_type":"func","_name":"Sample.method",' .
                '"args":[{"_type":"html","_name":"elt_id"}]}],' .
                '"confirm":{"lib":"noty","question":{"title":"","phrase":{"str":"Really?","args":[]}}},' .
                '"alert":{"lib":"cute","message":{"type":"info","title":"","phrase":{"str":"No confirm","args":[]}}}})',
            rq('Sample')->method(pm()->html('elt_id'))->confirm("Really?")
                ->elseInfo("No confirm")->__toString()
        );
    }

    /**
     * @throws SetupException
     */
    public function testConfirmMessageWarning()
    {
        jaxon()->register(Jaxon::CALLABLE_CLASS, 'Sample', __DIR__ . '/../src/sample.php');
        jaxon()->app()->setOption('dialogs.default.alert', 'cute');
        jaxon()->app()->setOption('dialogs.default.confirm', 'noty');
        $this->assertEquals(
            'jaxon.exec({"_type":"expr","calls":[{"_type":"func","_name":"Sample.method",' .
                '"args":[{"_type":"html","_name":"elt_id"}]}],' .
                '"confirm":{"lib":"noty","question":{"title":"","phrase":{"str":"Really?","args":[]}}},' .
                '"alert":{"lib":"cute","message":{"type":"warning","title":"","phrase":{"str":"No confirm","args":[]}}}})',
            rq('Sample')->method(pm()->html('elt_id'))->confirm("Really?")
                ->elseWarning("No confirm")->__toString()
        );
    }

    /**
     * @throws SetupException
     */
    public function testConfirmMessageError()
    {
        jaxon()->register(Jaxon::CALLABLE_CLASS, 'Sample', __DIR__ . '/../src/sample.php');
        jaxon()->app()->setOption('dialogs.default.alert', 'cute');
        jaxon()->app()->setOption('dialogs.default.confirm', 'noty');
        $this->assertEquals(
            'jaxon.exec({"_type":"expr","calls":[{"_type":"func","_name":"Sample.method",' .
                '"args":[{"_type":"html","_name":"elt_id"}]}],' .
                '"confirm":{"lib":"noty","question":{"title":"","phrase":{"str":"Really?","args":[]}}},' .
                '"alert":{"lib":"cute","message":{"type":"error","title":"","phrase":{"str":"No confirm","args":[]}}}})',
            rq('Sample')->method(pm()->html('elt_id'))->confirm("Really?")
                ->elseError("No confirm")->__toString()
        );
    }

    /**
     * @throws SetupException
     */
    public function testErrorRegisterIncorrectDialogClass()
    {
        $this->expectException(SetupException::class);
        dialog()->registerLibrary(Dialog::class, 'incorrect');
    }

    public function testErrorSetWrongAlertLibrary()
    {
        $this->expectException(SetupException::class);
        jaxon()->app()->setOption('dialogs.default.alert', 'incorrect');
    }

    public function testErrorSetWrongModalLibrary()
    {
        $this->expectException(SetupException::class);
        jaxon()->app()->setOption('dialogs.default.modal', 'incorrect');
    }

    public function testErrorSetWrongConfirmLibrary()
    {
        $this->expectException(SetupException::class);
        jaxon()->app()->setOption('dialogs.default.confirm', 'incorrect');
    }
}
