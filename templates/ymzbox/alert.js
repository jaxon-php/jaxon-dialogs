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
