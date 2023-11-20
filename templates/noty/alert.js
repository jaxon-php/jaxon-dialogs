jaxon.dialogs.noty = {
    success: (content, title) => noty({text: content, type: 'success', layout: 'topCenter', timeout: 5000}),
    info: (content, title) => noty({text: content, type: 'information', layout: 'topCenter', timeout: 5000}),
    warning: (content, title) => noty({text: content, type: 'warning', layout: 'topCenter', timeout: 5000}),
    error: (content, title) => noty({text: content, type: 'error', layout: 'topCenter', timeout: 5000}),
    confirm: (question, title, yesCallback, noCallback) => noty({
        text: question,
        layout: 'topCenter',
        buttons: [
            {
                addClass: 'btn btn-primary',
                text: "<?php echo $this->yes ?>",
                onClick: ($noty) => {
                    $noty.close();
                    yesCallback();
                }
            },{
                addClass: 'btn btn-danger',
                text: "<?php echo $this->no ?>",
                onClick: ($noty) => {
                    $noty.close();
                    noCallback && noCallback();
                }
            }
        ]
    }),
};
