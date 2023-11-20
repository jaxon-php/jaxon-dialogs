jaxon.dialogs.cutealert = {
    success: (content, title = 'Success') => cuteAlert({message: content, title: title, type: 'green'}),
    info: (content, title = 'Information') => cuteAlert({message: content, title: title, type: 'blue'}),
    warning: (content, title = 'Warning') => cuteAlert({message: content, title: title, type: 'yellow'}),
    error: (content, title = 'Error') => cuteAlert({message: content, title: title, type: 'red'}),
    confirm: (question, title, yesCallback, noCallback) => {
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
