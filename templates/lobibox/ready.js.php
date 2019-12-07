<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.lobibox.success;
    jaxon.ajax.message.info = jaxon.dialogs.lobibox.info;
    jaxon.ajax.message.warning = jaxon.dialogs.lobibox.warning;
    jaxon.ajax.message.error = jaxon.dialogs.lobibox.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.lobibox.confirm;
<?php endif ?>
    Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {sound: false, position: "top center", delayIndicator: false});
    Lobibox.window.DEFAULTS = $.extend({}, Lobibox.window.DEFAULTS, {width: 700, height: "auto"});

    jaxon.command.handler.register("lobibox.show", jaxon.dialogs.lobibox.show);
    jaxon.command.handler.register("lobibox.hide", jaxon.dialogs.lobibox.hide);
    jaxon.command.handler.register("lobibox.notify", function(args) {
        Lobibox.notify(args.data.type, {title: args.data.title, msg: args.data.message});
    });
