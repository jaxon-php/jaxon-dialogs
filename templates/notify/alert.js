jaxon.dialogs.notify = {
    success: function(content, title) {
        $.notify(content, {className: 'success', position: "top center"});
    },
    info: function(content, title) {
        $.notify(content, {className: 'info', position: "top center"});
    },
    warning: function(content, title) {
        $.notify(content, {className: 'warn', position: "top center"});
    },
    error: function(content, title) {
        $.notify(content, {className: 'error', position: "top center"});
    }
};

jaxon.dom.ready(function() {
    jaxon.command.handler.register("notify.alert", function(args) {
        $.notify(args.data.message, {className: args.data.className, position: "top center"});
    });

<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.notify.success;
    jaxon.ajax.message.info = jaxon.dialogs.notify.info;
    jaxon.ajax.message.warning = jaxon.dialogs.notify.warning;
    jaxon.ajax.message.error = jaxon.dialogs.notify.error;
<?php endif ?>
});
