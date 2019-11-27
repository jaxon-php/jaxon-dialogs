jaxon.dialogs.toastr = {
    success: function(content, title) {
        if((title))
            toastr.success(content, title);
        else
            toastr.success(content);
    },
    info: function(content, title) {
        if((title))
            toastr.info(content, title);
        else
            toastr.info(content);
    },
    warning: function(content, title) {
        if((title))
            toastr.warning(content, title);
        else
            toastr.warning(content);
    },
    error: function(content, title) {
        if((title))
            toastr.error(content, title);
        else
            toastr.error(content);
    }
};

jaxon.dom.ready(function() {
<?php echo $this->options ?>

    jaxon.command.handler.register("toastr.info", function(args) {
        jaxon.dialogs.toastr.info(args.data.message, args.data.title);
    });
    jaxon.command.handler.register("toastr.success", function(args) {
        jaxon.dialogs.toastr.success(args.data.message, args.data.title);
    });
    jaxon.command.handler.register("toastr.warning", function(args) {
        jaxon.dialogs.toastr.warning(args.data.message, args.data.title);
    });
    jaxon.command.handler.register("toastr.error", function(args) {
        jaxon.dialogs.toastr.error(args.data.message, args.data.title);
    });

<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.toastr.success;
    jaxon.ajax.message.info = jaxon.dialogs.toastr.info;
    jaxon.ajax.message.warning = jaxon.dialogs.toastr.warning;
    jaxon.ajax.message.error = jaxon.dialogs.toastr.error;
<?php endif ?>
});
