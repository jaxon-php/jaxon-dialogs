<?php

namespace Jaxon\Dialogs\Tests\TestDialog;

require_once __DIR__ . '/../src/dialog.php';

use Jaxon\Jaxon;
use Jaxon\Dialogs\DialogPlugin;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Dialogs\Dialog\Library\Bootbox;
use Jaxon\Dialogs\Dialog\Library\Bootstrap;
use Jaxon\Exception\SetupException;
use Jaxon\Utils\Http\UriException;
use PHPUnit\Framework\TestCase;

use ClassWithInterface;
use Dialog;
use TestDialogLibrary;

use function get_class;
use function Jaxon\jaxon;
use function Jaxon\Dialogs\dialog;
use function Jaxon\Dialogs\_register;

class SetupTest extends TestCase
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
        jaxon()->app()->setup(__DIR__ . '/../config/dialog.php');
        $this->assertStringContainsString('toast-top-center', jaxon()->script());

        /** @var DialogPlugin */
        $xDialogPlugin = jaxon()->di()->g(DialogPlugin::class);
        $this->assertStringContainsString('5.0.0', $xDialogPlugin->getHash());
    }

    public function testDialogDefaultMethods()
    {
        dialog()->registerLibrary(TestDialogLibrary::class, TestDialogLibrary::NAME);
        // Registering a library twice should not cause any issue.
        dialog()->registerLibrary(TestDialogLibrary::class, TestDialogLibrary::NAME);
        jaxon()->app()->setOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $xConfirmLibrary = dialog()->getConfirmLibrary();
        $this->assertEquals('', $xConfirmLibrary->getUri());
        $this->assertEquals('', $xConfirmLibrary->getJs());
        $this->assertEquals('', $xConfirmLibrary->getScript());
        $this->assertEquals(null, $xConfirmLibrary->getJsCode());
    }

    public function testExtDialogLibrary()
    {
        dialog()->registerLibrary(TestDialogLibrary::class, TestDialogLibrary::NAME);
        jaxon()->app()->setOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
    }

    public function testExtDialogLibraryConfigSet()
    {
        jaxon()->app()->setOption('dialogs.lib.ext', [
            TestDialogLibrary::NAME => TestDialogLibrary::class,
        ]);
        jaxon()->app()->setOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
    }

    public function testExtDialogLibraryConfigFile()
    {
        jaxon()->app()->setup(__DIR__ . '/../config/ext.php');
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
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
        $this->assertStringContainsString("jaxon.dialog.register('bootstrap'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('bootbox'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('cute'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('jalert'", $sScriptCode);
    }

    public function testAlertifyLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['alertify']);

        $this->assertStringContainsString('alertify.min.js', jaxon()->js());

        $this->assertStringContainsString('css/alertify.min.css', jaxon()->css());
        $this->assertStringContainsString('css/themes/default.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('alertify'", jaxon()->script());
    }

    public function testBootboxLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['bootbox']);

        $this->assertStringContainsString('bootbox.min.js', jaxon()->js());

        $this->assertStringContainsString("jaxon.dialog.register('bootbox'", jaxon()->script());
    }

    public function testBootstrapLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['bootstrap']);

        $this->assertStringContainsString('js/bootstrap-dialog.min.js', jaxon()->js());

        $this->assertStringContainsString('css/bootstrap-dialog.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('bootstrap'", jaxon()->script());
    }

    public function testCuteAlertLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['cute']);

        $this->assertStringContainsString('cute-alert/cute-alert.js', jaxon()->js());

        $this->assertStringContainsString('cute-alert/style.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('cute'", jaxon()->script());
    }

    public function testJAlertLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['jalert']);

        $this->assertStringContainsString('jAlert.min.js', jaxon()->js());

        $this->assertStringContainsString('jAlert.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('jalert'", jaxon()->script());
    }

    public function testJQueryConfirmLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['jconfirm']);

        $this->assertStringContainsString('jquery-confirm.min.js', jaxon()->js());

        $this->assertStringContainsString('jquery-confirm.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('jconfirm'", jaxon()->script());
    }

    public function testNotifyLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['notify']);

        $this->assertStringContainsString('notify.min.js', jaxon()->js());

        $this->assertStringContainsString("jaxon.dialog.register('notify'", jaxon()->script());
    }

    public function testNotyLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['noty']);

        $this->assertStringContainsString('noty.min.js', jaxon()->js());

        $this->assertStringContainsString('noty.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('noty'", jaxon()->script());
    }

    public function testSweetAlertLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['sweetalert']);

        $this->assertStringContainsString('sweetalert.min.js', jaxon()->js());

        $this->assertStringContainsString("jaxon.dialog.register('sweetalert'", jaxon()->script());
    }

    public function testTingleLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['tingle']);

        $this->assertStringContainsString('tingle.min.js', jaxon()->js());

        $this->assertStringContainsString('tingle.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('tingle'", jaxon()->script());
    }

    public function testToastrLibrary()
    {
        jaxon()->app()->setOption('dialogs.lib.use', ['toastr']);

        $this->assertStringContainsString('js/toastr.min.js', jaxon()->js());

        $this->assertStringContainsString('css/toastr.min.css', jaxon()->css());

        $this->assertStringContainsString("jaxon.dialog.register('toastr'", jaxon()->script());
    }

    /**
     * @throws SetupException
     */
    public function testErrorRegisterIncorrectDialogClass()
    {
        $this->expectException(SetupException::class);
        dialog()->registerLibrary(Dialog::class, 'incorrect');
    }

    public function testErrorRegisterIncorrectDialogClassWithInterface()
    {
        $this->expectException(SetupException::class);
        dialog()->registerLibrary(ClassWithInterface::class, 'incorrect');
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
