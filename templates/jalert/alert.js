jaxon.dialogs.jalert = {
    success: function(content, title = 'Success') {
        $.jAlert({content: content, title: title, theme: 'green'});
    },
    info: function(content, title = 'Information') {
        $.jAlert({content: content, title: title, theme: 'blue'});
    },
    warning: function(content, title = 'Warning') {
        $.jAlert({content: content, title: title, theme: 'yellow'});
    },
    error: function(content, title = 'Error') {
        $.jAlert({content: content, title: title, theme: 'red'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        if(noCallback === undefined)
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
