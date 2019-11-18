jaxon.dialogs.jalert = {
    success: function(content, title) {
        if(title == undefined) title = 'Success';
        $.jAlert({content: content, title: title, theme: 'green'});
    },
    info: function(content, title) {
        if(title == undefined) title = 'Information';
        $.jAlert({content: content, title: title, theme: 'blue'});
    },
    warning: function(content, title) {
        if(title == undefined) title = 'Warning';
        $.jAlert({content: content, title: title, theme: 'yellow'});
    },
    error: function(content, title) {
        if(title == undefined) title = 'Error';
        $.jAlert({content: content, title: title, theme: 'red'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        if(noCallback == undefined)
            noCallback = function(){};
        $.jAlert({
            title: title,
            type: "confirm",
            confirmQuestion: question,
            confirmBtnText: "<?php echo $this->yes ?>",
            denyBtnText: "<?php echo $this->no ?>",
            onConfirm: yesCallback,
            onDeny: noCallback
        });
    }
};

jaxon.dom.ready(function() {
    jaxon.command.handler.register("jalert.alert", function(args) {
        $.jAlert(args.data);
    });

<?php if(($this->defaultForAlert)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.jalert.success;
    jaxon.ajax.message.info = jaxon.dialogs.jalert.info;
    jaxon.ajax.message.warning = jaxon.dialogs.jalert.warning;
    jaxon.ajax.message.error = jaxon.dialogs.jalert.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.jalert.confirm;
<?php endif ?>
});
