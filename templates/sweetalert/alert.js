jaxon.dialogs.swal = {
    options: {
        allowEscapeKey: true,
        allowOutsideClick: true
    },
    success: (content, title = 'Success') => swal({text: content, title: title, type: 'success'}),
    info: (content, title = 'Information') => swal({text: content, title: title, type: 'info'}),
    warning: (content, title = 'Warning') => swal({text: content, title: title, type: 'warning'}),
    error: (content, title = 'Error') => swal({text: content, title: title, type: 'error'}),
    confirm: (question, title, yesCallback, noCallback) => swal({
        type: "warning",
        title: title,
        confirmButtonText: "<?php echo $this->yes ?>",
        cancelButtonText: "<?php echo $this->no ?>",
        showCancelButton: true,
        text: question
    },
    (res) => {
        if(res)
            yesCallback();
        else if((noCallback))
            noCallback();
    }),
};
