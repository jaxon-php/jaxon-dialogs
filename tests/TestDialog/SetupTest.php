<?php

namespace Jaxon\Dialogs\Tests\TestDialog;

require_once __DIR__ . '/../src/dialog.php';

use Jaxon\Jaxon;
use Jaxon\Dialogs\DialogPlugin;
use Jaxon\Dialogs\Dialog\Library\Alert;
use Jaxon\Dialogs\Dialog\Library\Alertify;
use Jaxon\Dialogs\Dialog\Library\Bootbox;
use Jaxon\Exception\SetupException;
use Jaxon\Utils\Http\UriException;
use PHPUnit\Framework\TestCase;

use ClassWithInterface;
use Dialog;
use TestDialogLibrary;

use function get_class;
use function Jaxon\jaxon;
use function Jaxon\Dialogs\dialog;
use function Jaxon\Dialogs\_register as register_dialogs;

class SetupTest extends TestCase
{
    /**
     * @throws SetupException
     */
    public function setUp(): void
    {
        register_dialogs();

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

        jaxon()->setAppOptions([
            'modal' => 'alertify',
            'alert' => 'alertify',
            'confirm' => 'alertify',
        ], 'dialogs.default');
        $this->assertEquals(Alertify::class, get_class(dialog()->getConfirmLibrary()));
        $this->assertEquals(Alertify::class, get_class(dialog()->getAlertLibrary()));
        $this->assertEquals(Alertify::class, get_class(dialog()->getModalLibrary()));

        jaxon()->setAppOptions([
            'modal' => 'bootbox',
            'alert' => 'bootbox',
            'confirm' => 'bootbox',
        ], 'dialogs.default');
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
        jaxon()->setAppOption('dialogs.default.confirm', TestDialogLibrary::NAME);

        $xConfirmLibrary = dialog()->getConfirmLibrary();
        $this->assertEmpty($xConfirmLibrary->getJsUrls());
        $this->assertEmpty($xConfirmLibrary->getCssUrls());
        $this->assertEquals('', $xConfirmLibrary->getJsCode());
        $this->assertEquals('', $xConfirmLibrary->getCssCode());
    }

    public function testExtDialogLibrary()
    {
        dialog()->registerLibrary(TestDialogLibrary::class, TestDialogLibrary::NAME);
        jaxon()->setAppOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
    }

    public function testExtDialogLibraryConfigSet()
    {
        jaxon()->setAppOption('dialogs.lib.ext', [
            TestDialogLibrary::NAME => TestDialogLibrary::class,
        ]);
        jaxon()->setAppOption('dialogs.default.confirm', TestDialogLibrary::NAME);
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
    }

    public function testExtDialogLibraryConfigFile()
    {
        jaxon()->app()->setup(__DIR__ . '/../config/ext.php');
        $this->assertEquals(TestDialogLibrary::class, get_class(dialog()->getConfirmLibrary()));
    }

    public function testDialogJsCode()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['bootbox', 'alertify', 'cute']);
        $sJsCode = jaxon()->js();
        $this->assertStringContainsString('bootbox.min.js', $sJsCode);
        $this->assertStringContainsString('alertify.min.js', $sJsCode);
        $this->assertStringContainsString('cute-alert.js', $sJsCode);
    }

    public function testDialogCssCode()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['alertify', 'cute']);
        $sCssCode = jaxon()->css();
        $this->assertStringContainsString('alertify.min.css', $sCssCode);
        $this->assertStringContainsString('cute-alert/style.css', $sCssCode);
    }

    /**
     * @throws UriException
     */
    public function testDialogScriptCode()
    {
        jaxon()->setAppOptions([
            'default' => [
                'modal' => 'alertify',
                'alert' => 'alertify',
                'confirm' => 'alertify',
            ],
            'lib' => [
                'use' => ['bootbox', 'cute', 'jalert'],
            ],
        ], 'dialogs');

        $sScriptCode = jaxon()->getScript();
        $this->assertStringContainsString("jaxon.dialog.register('alertify'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('bootbox'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('cute'", $sScriptCode);
        $this->assertStringContainsString("jaxon.dialog.register('jalert'", $sScriptCode);
    }

    /**
     * @throws UriException
     */
    public function testDialogScriptFile()
    {
        jaxon()->setAppOptions([
            'export' => true,
            'minify' => false,
            'file' => 'app.dialog',
            'dir' => __DIR__ . '/../js',
            'uri' => 'http://localhost',
        ], 'assets.js');
        jaxon()->setAppOptions([
            'default' => [
                'modal' => 'alertify',
                'alert' => 'alertify',
                'confirm' => 'alertify',
            ],
            'lib' => [
                'use' => ['bootbox', 'cute', 'jalert'],
            ],
        ], 'dialogs');

        $sScriptCode = jaxon()->getScript();
        $this->assertStringNotContainsString("jaxon.dialog.register('alertify'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('bootbox'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('cute'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('jalert'", $sScriptCode);

        $this->assertStringContainsString('app.dialog.js', $sScriptCode);
    }

    /**
     * @throws UriException
     */
    public function testDialogScriptMinFile()
    {
        jaxon()->setAppOptions([
            'export' => true,
            'minify' => true,
            'file' => 'app.dialog',
            'dir' => __DIR__ . '/../js',
            'uri' => 'http://localhost',
        ], 'assets.js');
        jaxon()->setAppOptions([
            'default' => [
                'modal' => 'alertify',
                'alert' => 'alertify',
                'confirm' => 'alertify',
            ],
            'lib' => [
                'use' => ['bootbox', 'cute', 'jalert'],
            ],
        ], 'dialogs');

        $sScriptCode = jaxon()->getScript();
        $this->assertStringNotContainsString("jaxon.dialog.register('alertify'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('bootbox'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('cute'", $sScriptCode);
        $this->assertStringNotContainsString("jaxon.dialog.register('jalert'", $sScriptCode);

        $this->assertStringContainsString('app.dialog.min.js', $sScriptCode);
    }

    public function testAlertifyLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['alertify']);

        $this->assertStringContainsString('alertify.min.js', jaxon()->js());
        $this->assertStringContainsString('css/alertify.min.css', jaxon()->css());
        $this->assertStringContainsString('css/themes/default.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('alertify'", jaxon()->script());
    }

    public function testBootboxLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['bootbox']);

        $this->assertStringContainsString('bootbox.min.js', jaxon()->js());
        $this->assertStringContainsString("jaxon.dialog.register('bootbox'", jaxon()->script());
    }

    public function testBootstrap3Library()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['bootstrap3']);

        $this->assertStringContainsString("jaxon.dialog.register('bootstrap3'", jaxon()->script());
    }

    public function testBootstrap4Library()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['bootstrap4']);

        $this->assertStringContainsString("jaxon.dialog.register('bootstrap4'", jaxon()->script());
    }

    public function testBootstrap5Library()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['bootstrap5']);

        $this->assertStringContainsString("jaxon.dialog.register('bootstrap5'", jaxon()->script());
    }

    public function testButterupLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['butterup']);

        $this->assertStringContainsString('butterup.min.js', jaxon()->js());
        $this->assertStringContainsString('butterup.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('butterup'", jaxon()->script());
    }

    public function testCuteAlertLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['cute']);

        $this->assertStringContainsString('cute-alert/cute-alert.js', jaxon()->js());
        $this->assertStringContainsString('cute-alert/style.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('cute'", jaxon()->script());
    }

    public function testIziToastLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['izitoast']);

        $this->assertStringContainsString('js/iziToast.min.js', jaxon()->js());
        $this->assertStringContainsString('css/iziToast.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('izitoast'", jaxon()->script());
    }

    public function testJAlertLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['jalert']);

        $this->assertStringContainsString('jAlert.min.js', jaxon()->js());
        $this->assertStringContainsString('jAlert.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('jalert'", jaxon()->script());
    }

    public function testJQueryConfirmLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['jconfirm']);

        $this->assertStringContainsString('jquery-confirm.min.js', jaxon()->js());
        $this->assertStringContainsString('jquery-confirm.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('jconfirm'", jaxon()->script());
    }

    public function testNotifyLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['notify']);

        $this->assertStringContainsString('notify.min.js', jaxon()->js());
        $this->assertStringContainsString("jaxon.dialog.register('notify'", jaxon()->script());
    }

    public function testNotyLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['noty']);

        $this->assertStringContainsString('noty.min.js', jaxon()->js());
        $this->assertStringContainsString('noty.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('noty'", jaxon()->script());
    }

    public function testNotyfLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['notyf']);

        $this->assertStringContainsString('notyf.min.js', jaxon()->js());
        $this->assertStringContainsString('notyf.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('notyf'", jaxon()->script());
    }

    public function testQuantumLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['quantum']);

        $this->assertStringContainsString('minfile/quantumalert.js', jaxon()->js());
        $this->assertStringContainsString("jaxon.dialog.register('quantum'", jaxon()->script());
    }

    public function testSweetAlertLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['sweetalert']);

        $this->assertStringContainsString('sweetalert.min.js', jaxon()->js());
        $this->assertStringContainsString("jaxon.dialog.register('sweetalert'", jaxon()->script());
    }

    public function testTingleLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['tingle']);

        $this->assertStringContainsString('tingle.min.js', jaxon()->js());
        $this->assertStringContainsString('tingle.min.css', jaxon()->css());
        $this->assertStringContainsString("jaxon.dialog.register('tingle'", jaxon()->script());
    }

    public function testToastrLibrary()
    {
        jaxon()->setAppOption('dialogs.lib.use', ['toastr']);

        $this->assertStringContainsString('toastr.min.js', jaxon()->js());
        $this->assertStringContainsString('build/toastr.min.css', jaxon()->css());
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
        jaxon()->setAppOption('dialogs.default.alert', 'incorrect');
        $xLibrary = dialog()->getAlertLibrary();
    }

    public function testErrorSetWrongModalLibrary()
    {
        $this->expectException(SetupException::class);
        jaxon()->setAppOption('dialogs.default.modal', 'incorrect');
        $xLibrary = dialog()->getModalLibrary();
    }

    public function testErrorSetWrongConfirmLibrary()
    {
        $this->expectException(SetupException::class);
        jaxon()->setAppOption('dialogs.default.confirm', 'incorrect');
        $xLibrary = dialog()->getConfirmLibrary();
    }
}
