/*
 * Bootbox dialogs plugin
 */
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
