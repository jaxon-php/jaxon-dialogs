<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.pnotify.success;
    jaxon.ajax.message.info = jaxon.dialogs.pnotify.info;
    jaxon.ajax.message.warning = jaxon.dialogs.pnotify.warning;
    jaxon.ajax.message.error = jaxon.dialogs.pnotify.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.pnotify.confirm;
<?php endif ?>
    PNotify.prototype.options.delay = 5000;
    PNotify.prototype.options.styling = 'fontawesome';
<?php echo $this->options ?>
    jaxon.command.handler.register("pnotify.alert", function(args) {
        jaxon.pnotify.alert(args.data);
    });
