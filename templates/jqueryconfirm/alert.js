jaxon.dialogs.jconfirm = {
    dialog: null,
    success: function(content, title) {
        $.alert({content: content, title: title, type: 'green', icon: 'fa fa-success'});
    },
    info: function(content, title) {
        $.alert({content: content, title: title, type: 'blue', icon: 'fa fa-info'});
    },
    warning: function(content, title) {
        $.alert({content: content, title: title, type: 'orange', icon: 'fa fa-warning'});
    },
    error: function(content, title) {
        $.alert({content: content, title: title, type: 'red', icon: 'fa fa-error'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        if(noCallback == undefined)
            noCallback = function(){};
        $.confirm({
            title: title,
            content: question,
            buttons: {
                yes: {
                    btnClass: "btn-blue",
                    text: "<?php echo $this->yes ?>",
                    action: yesCallback
                },
                no: {
                    text: "<?php echo $this->no ?>",
                    action: noCallback
                }
            }
        });
    }
};

jaxon.command.handler.register("jconfirm.show", function(args) {
    // Add buttons
    for(key in args.data.buttons)
    {
        button = args.data.buttons[key];
        if(button.action == "close")
        {
            button.action = function(){jaxon.dialogs.jconfirm.dialog.close();};
        }
        else
        {
            button.action = new Function(button.action);
        }
    }
    args.data.closeIcon = true;
    if((jaxon.dialogs.jconfirm.dialog))
    {
        jaxon.dialogs.jconfirm.dialog.close();
    }
    jaxon.dialogs.jconfirm.dialog = $.confirm(args.data);
});
jaxon.command.handler.register("jconfirm.hide", function(args) {
    if((jaxon.dialogs.jconfirm.dialog))
    {
        jaxon.dialogs.jconfirm.dialog.close();
    }
    jaxon.dialogs.jconfirm.dialog = null;
});
jaxon.command.handler.register("jconfirm.alert", function(args) {
    $.alert(args.data);
});

<?php if(($this->defaultForAlert)): ?>
jaxon.ajax.message.success = jaxon.dialogs.jconfirm.success;
jaxon.ajax.message.info = jaxon.dialogs.jconfirm.info;
jaxon.ajax.message.warning = jaxon.dialogs.jconfirm.warning;
jaxon.ajax.message.error = jaxon.dialogs.jconfirm.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
jaxon.ajax.message.confirm = jaxon.dialogs.jconfirm.confirm;
<?php endif ?>
