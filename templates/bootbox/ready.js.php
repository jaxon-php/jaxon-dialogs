/*
 * Bootbox dialogs plugin
 */
    if(!$('#<?php echo $this->container ?>').length)
    {
        $('body').append('<div id="<?php echo $this->container ?>"></div>');
    }
    jaxon.command.handler.register("bootbox", function(args) {
        bootbox.alert(args.data.type, args.data.content, args.data.title);
    });
<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.bootbox.success;
    jaxon.ajax.message.info = jaxon.dialogs.bootbox.info;
    jaxon.ajax.message.warning = jaxon.dialogs.bootbox.warning;
    jaxon.ajax.message.error = jaxon.dialogs.bootbox.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.bootbox.confirm;
<?php endif;
