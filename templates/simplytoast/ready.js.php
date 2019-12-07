<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.simplytoast.success;
    jaxon.ajax.message.info = jaxon.dialogs.simplytoast.info;
    jaxon.ajax.message.warning = jaxon.dialogs.simplytoast.warning;
    jaxon.ajax.message.error = jaxon.dialogs.simplytoast.error;
<?php endif ?>
    jaxon.command.handler.register("simply.alert", function(args) {
        $.simplyToast(args.data.message, args.data.type, <?php echo $this->options ?>);
    });
