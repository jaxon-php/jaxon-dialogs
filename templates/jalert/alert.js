jaxon.dialogs.jalert = {
    success: (content, title = 'Success') => $.jAlert({content: content, title: title, theme: 'green'}),
    info: (content, title = 'Information') => $.jAlert({content: content, title: title, theme: 'blue'}),
    warning: (content, title = 'Warning') => $.jAlert({content: content, title: title, theme: 'yellow'}),
    error: (content, title = 'Error') => $.jAlert({content: content, title: title, theme: 'red'}),
    confirm: (question, title, yesCallback, noCallback) => $.jAlert({
        title: title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: "<?php echo $this->yes ?>",
        denyBtnText: "<?php echo $this->no ?>",
        onConfirm: yesCallback,
        onDeny: noCallback ?? (() => {}),
    }),
};
