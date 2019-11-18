jaxon.dialogs.overhang = {
    success: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'success', duration: 5});
    },
    info: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'info', duration: 5});
    },
    warning: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'warn', duration: 5});
    },
    error: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'error', duration: 5});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        $("body").overhang({
            type: "confirm",
            message: question,
            callback: function(res){
                if(res)
                    yesCallback();
                else if(noCallback != undefined)
                    noCallback();
            }
        });
    }
};

jaxon.dom.ready(function() {
    jaxon.command.handler.register("overhang.alert", function(args) {
        // Default options
        args.data.duration = 5;
        $("body").overhang(args.data);
    });

<?php if(($this->defaultForAlert)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.overhang.success;
    jaxon.ajax.message.info = jaxon.dialogs.overhang.info;
    jaxon.ajax.message.warning = jaxon.dialogs.overhang.warning;
    jaxon.ajax.message.error = jaxon.dialogs.overhang.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.overhang.confirm;
<?php endif ?>
});
