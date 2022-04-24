jaxon.dialogs.cutealert = {
    success: function(content, title = 'Success') {
        cuteAlert({message: content, title: title, type: 'green'});
    },
    info: function(content, title = 'Information') {
        cuteAlert({message: content, title: title, type: 'blue'});
    },
    warning: function(content, title = 'Warning') {
        cuteAlert({message: content, title: title, type: 'yellow'});
    },
    error: function(content, title = 'Error') {
        cuteAlert({message: content, title: title, type: 'red'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        cuteAlert({
            title: title,
            type: 'question',
            message: question,
            confirmText: "<?php echo $this->yes ?>",
            cancelText: "<?php echo $this->no ?>",
        }).then(e => {
            if(e === 'confirm')
            {
                yesCallback();
            }
            else if((noCallback))
            {
                noCallback();
            }
        });
    }
};
