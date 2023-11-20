/*
 * Bootbox dialogs plugin
 */
jaxon.dialogs.bootbox = {
    alert: (type, content, title) => {
        let html = '<div class="alert alert-' + type + '" style="margin-top:15px;margin-bottom:-15px;">';
        if(title != undefined && title != '')
            html += '<strong>' + title + '</strong><br/>';
        html += content + '</div>';
        bootbox.alert(html);
    },
    success: (content, title) => jaxon.dialogs.bootbox.alert('success', content, title),
    info: (content, title) => jaxon.dialogs.bootbox.alert('info', content, title),
    warning: (content, title) => jaxon.dialogs.bootbox.alert('warning', content, title),
    error: (content, title) => jaxon.dialogs.bootbox.alert('danger', content, title),
    confirm: (question, title, yesCallback, noCallback) => bootbox.confirm({
        title: title,
        message: question,
        buttons: {
            cancel: {label: "<?php echo $this->no ?>"},
            confirm: {label: "<?php echo $this->yes ?>"}
        },
        callback: (res) => {
            if(res)
                yesCallback();
            else if((noCallback))
                noCallback();
        }
    }),
};
