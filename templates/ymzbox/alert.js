jaxon.dialogs.ymzbox = {
    duration: 3,
    success: function(content, title) {
        ymz.jq_toast({text: content, title: title, sec: jaxon.dialogs.ymzbox.duration, type: 'success'});
    },
    info: function(content, title) {
        ymz.jq_toast({text: content, title: title, sec: jaxon.dialogs.ymzbox.duration, type: 'notice'});
    },
    warning: function(content, title) {
        ymz.jq_toast({text: content, title: title, sec: jaxon.dialogs.ymzbox.duration, type: 'warning'});
    },
    error: function(content, title) {
        ymz.jq_toast({text: content, title: title, sec: jaxon.dialogs.ymzbox.duration, type: 'error'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        if(noCallback == undefined)
            noCallback = function(){};
        ymz.jq_confirm({
            title: title,
            text: question,
            no_fn: noCallback,
            yes_fn: yesCallback,
            no_btn: "<?php echo $this->no ?>",
            yes_btn: "<?php echo $this->yes ?>"
        });
    }
};

jaxon.dom.ready(function() {
<?php echo $this->options ?>

    jaxon.command.handler.register("ymzbox.alert", function(args) {
        ymz.jq_toast(args.data);
    });

<?php if(($this->defaultForAlert)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.ymzbox.success;
    jaxon.ajax.message.info = jaxon.dialogs.ymzbox.info;
    jaxon.ajax.message.warning = jaxon.dialogs.ymzbox.warning;
    jaxon.ajax.message.error = jaxon.dialogs.ymzbox.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.ymzbox.confirm;
<?php endif ?>
});
