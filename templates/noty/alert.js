jaxon.dialogs.noty = {
    success: function(content, title) {
        noty({text: content, type: 'success', layout: 'topCenter', timeout: 5000});
    },
    info: function(content, title) {
        noty({text: content, type: 'information', layout: 'topCenter', timeout: 5000});
    },
    warning: function(content, title) {
        noty({text: content, type: 'warning', layout: 'topCenter', timeout: 5000});
    },
    error: function(content, title) {
        noty({text: content, type: 'error', layout: 'topCenter', timeout: 5000});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        noty({
            text: question,
            layout: 'topCenter',
            buttons: [
                {
                    addClass: 'btn btn-primary',
                    text: "<?php echo $this->yes ?>",
                    onClick: function($noty){
                        $noty.close();
                        yesCallback();
                    }
                },{
                    addClass: 'btn btn-danger',
                    text: "<?php echo $this->no ?>",
                    onClick: function($noty){
                        $noty.close();
                        if(noCallback != undefined)
                            noCallback();
                    }
                }
            ]
        });
    }
};
