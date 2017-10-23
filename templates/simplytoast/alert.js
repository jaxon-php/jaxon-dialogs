jaxon.dialogs.simplytoast = {
    success: function(content, title) {
        $.simplyToast(content, 'success', <?php echo $this->options ?>);
    },
    info: function(content, title) {
        $.simplyToast(content, 'info', <?php echo $this->options ?>);
    },
    warning: function(content, title) {
        $.simplyToast(content, 'warning', <?php echo $this->options ?>);
    },
    error: function(content, title) {
        $.simplyToast(content, 'danger', <?php echo $this->options ?>);
    }
};

jaxon.command.handler.register("simply.alert", function(args) {
    $.simplyToast(args.data.message, args.data.type, <?php echo $this->options ?>);
});

<?php if(($this->defaultForAlert)): ?>
jaxon.ajax.message.success = jaxon.dialogs.simplytoast.success;
jaxon.ajax.message.info = jaxon.dialogs.simplytoast.info;
jaxon.ajax.message.warning = jaxon.dialogs.simplytoast.warning;
jaxon.ajax.message.error = jaxon.dialogs.simplytoast.error;
<?php endif ?>
