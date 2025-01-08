/**
 * Class: jaxon.dialog.lib.jconfirm
 */

jaxon.dialog.lib.register('jconfirm', (self, { js, types, labels }) => {
    /**
     * @var {object}
     */
    const dialog = {
        dom: null,
    };

    /**
     * Show the modal dialog
     *
     * @param {string} title The dialog title
     * @param {string} content The dialog HTML content
     * @param {array} buttons The dialog buttons
     * @param {array} options The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = function(title, content, buttons, options, jsElement) {
        self.hide();

        // Add buttons
        const xButtons = {};
        buttons.forEach(({ title: text, class: btnClass, click }, btnIndex) => {
            const handler = types.isObject(click) ? () => js.execExpr(click) : () => self.hide();
            xButtons['btn' + btnIndex] = { text, btnClass, action: handler };
        });

        dialog.dom = $.confirm({
            title,
            content,
            ...options,
            closeIcon: true,
            useBootstrap: true,
            boxWidth: 600,
            buttons: xButtons,
            // Pass the js content element to the callback.
            onContentReady: () => jsElement(dialog.dom.$jconfirmBox.get(0)),
        });
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if(!dialog.dom)
        {
            return;
        }
        dialog.dom.close();
        dialog.dom = null;
    };

    const xTypes = {
        success: 'green',
        info: 'blue',
        warning: 'orange',
        error: 'red',
    };

    const xIcons = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'error',
    };

    /**
     * Show an alert message
     *
     * @param {string} type The message type
     * @param {string} text The message text
     * @param {string} title The message title
     *
     * @returns {void}
     */
    self.alert = (type, text, title) => $.alert({
        content: text,
        title,
        type: xTypes[type] ?? xTypes.info,
        icon: 'fa fa-' + (xIcons[type] ?? xIcons.info),
    });

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => $.confirm({
        title,
        content: question,
        buttons: {
            yes: {
                btnClass: "btn-blue",
                text: labels.yes,
                action: yesCallback,
            },
            no: {
                text: labels.no,
                action: noCallback ?? (() => {}),
            },
        },
    });
});
