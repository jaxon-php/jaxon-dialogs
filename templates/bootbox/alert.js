jaxon.dialogs.bootbox = {
    alert: function(type, content, title) {
        var html = '<div class="alert alert-' + type + '" style="margin-top:15px;margin-bottom:-15px;">';
        if(title != undefined && title != '')
            html += '<strong>' + title + '</strong><br/>';
        html += content + '</div>';
        bootbox.alert(html);
    },
    success: function(content, title) {
        jaxon.dialogs.bootbox.alert('success', content, title);
    },
    info: function(content, title) {
        jaxon.dialogs.bootbox.alert('info', content, title);
    },
    warning: function(content, title) {
        jaxon.dialogs.bootbox.alert('warning', content, title);
    },
    error: function(content, title) {
        jaxon.dialogs.bootbox.alert('danger', content, title);
    },
    confirm: function(question, title, yesCallback, noCallback) {
        bootbox.confirm({
            title: title,
            message: question,
            buttons: {
                cancel: {label: "<?php echo $this->no ?>"},
                confirm: {label: "<?php echo $this->yes ?>"}
            },
            callback: function(res){
                if(res)
                    yesCallback();
                else if(typeof noCallback == 'function')
                    noCallback();
            }
        });
    }
};

if(!$('#<?php echo $this->container ?>').length)
{
    $('body').append('<div id="<?php echo $this->container ?>"></div>');
}
jaxon.command.handler.register("bootbox", function(args) {
    bootbox.alert(args.data.type, args.data.content, args.data.title);
});
<?php if(($this->defaultForAlert)): ?>
jaxon.ajax.message.success = jaxon.dialogs.bootbox.success;
jaxon.ajax.message.info = jaxon.dialogs.bootbox.info;
jaxon.ajax.message.warning = jaxon.dialogs.bootbox.warning;
jaxon.ajax.message.error = jaxon.dialogs.bootbox.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
jaxon.ajax.message.confirm = jaxon.dialogs.bootbox.confirm;
<?php endif ?>
