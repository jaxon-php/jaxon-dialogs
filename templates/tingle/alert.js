jaxon.dialogs.tingle = {
    modal: null,
    show: (args) => {
        if(jaxon.dialogs.tingle.modal != null)
        {
            jaxon.dialogs.tingle.modal.close();
        }
        jaxon.dialogs.tingle.modal = new tingle.modal(args.data.options);
        // Set content
        jaxon.dialogs.tingle.modal.setContent(args.data.content);
        // Add buttons
        for(let ind = 0, len = args.data.buttons.length; ind < len; ind++)
        {
            button = args.data.buttons[ind];
            button.click = button.click !== "close" ? new Function(button.click) :
                () => jaxon.dialogs.tingle.modal.close();
            jaxon.dialogs.tingle.modal.addFooterBtn(button.title, button.class, button.click);
        }
        // Open modal
        jaxon.dialogs.tingle.modal.open();
    },
    hide: (args) => {
        if(jaxon.dialogs.tingle.modal != null)
        {
            // Close an destroy modal
            jaxon.dialogs.tingle.modal.close();
            jaxon.dialogs.tingle.modal.destroy();
            jaxon.dialogs.tingle.modal = null;
        }
    }
};
