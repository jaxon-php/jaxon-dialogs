/*
 * Alertify dialogs plugin
 * Class: jaxon.dialog.lib.alertify
 */

jaxon.dialog.lib.register('alertify', (self, { js, types, labels, options = {} }) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

    /**
     * @var {object}
     */
    const dialog = {
        // name: 'jaxon_dialog',
        instance: null,
    };

    if(!alertify.jaxon_dialog) {
        alertify.dialog('jaxon_dialog', function factory() {
            return {
                main: function(message) {
                    this.message = message;
                },
                setup: function() {
                    return {
                        options: {
                            resizable: false,
                            maximizable: false,
                            ...dialog.options,
                            ...modalOptions,
                            title: dialog.title,
                        },
                        buttons: dialog.buttons.map(({ title: text, class: btnClass }) =>
                            ({ text, attrs: { class: btnClass } })),
                        // focus: {
                        //     element: 0,
                        // },
                    };
                },
                prepare: function() {
                    this.setContent(this.message);
                    // Pass the js content element to the callback.
                    dialog.jsElement(this.elements.content);
                    // Save the dialog instance locally.
                    dialog.instance = this;
                },
                build: function() {
                    // Pass the js content element to the callback.
                    // dialog.jsElement(this.elements.content);
                },
                callback:function(closeEvent) {
                    const button = dialog.buttons[closeEvent.index];
                    if (!button  || !types.isObject(button.click)) {
                        return; // The dialog will be closed.
                    }
                    // Prevent the dialog from closing.
                    closeEvent.cancel = true;
                    // Execute the button onclick handler.
                    js.execExpr(button.click);
                },
            };
        }, false);
    }

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
    self.show = (title, content, buttons, options, jsElement) => {
        dialog.title = title;
        dialog.buttons = buttons;
        dialog.options = options;
        dialog.jsElement = jsElement;
        alertify.jaxon_dialog(content);
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        dialog.instance && dialog.instance.close();
        dialog.instance = null;
    };

    const xTypes = {
        success: 'success',
        info: 'message',
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
    self.alert = (type, text, title) => alertify.notify(text, xTypes[type] ?? xTypes.info);

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => alertify
        .confirm(title ?? '&nbsp;', question, yesCallback, noCallback)
        .set('labels', { ok: labels.yes, cancel: labels.no });
});
