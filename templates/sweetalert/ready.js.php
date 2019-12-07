<?php echo $this->options ?>
<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.swal.success;
    jaxon.ajax.message.info = jaxon.dialogs.swal.info;
    jaxon.ajax.message.warning = jaxon.dialogs.swal.warning;
    jaxon.ajax.message.error = jaxon.dialogs.swal.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.swal.confirm;
<?php endif ?>
    jaxon.command.handler.register("sweetalert.alert", function(args) {
        // Set user and default options into data only when they are missing
        for(key in jaxon.dialogs.swal.options)
        {
            if(!(key in args.data))
            {
                args.data[key] = jaxon.dialogs.swal.options[key];
            }
        }
        swal(args.data);
    });
