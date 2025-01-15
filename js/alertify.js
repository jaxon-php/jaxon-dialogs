/*
 * Alertify dialogs plugin
 * Class: jaxon.dialog.libs.alertify
 */

jaxon.dialog.register('alertify', (self, options, utils) => {
    // Dialogs options
    const {
        labels,
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

    /**
     * Show the modal dialog
     *
     * @param {object} dialog The dialog parameters
     * @param {string} dialog.title The dialog title
     * @param {string} dialog.content The dialog HTML content
     * @param {array} dialog.buttons The dialog buttons
     * @param {array} dialog.options The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = ({ title, content, buttons, options }, jsElement) => {
        // Create the dialog factory.
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
                    if (!button  || !utils.isObject(button.click)) {
                        return; // The dialog will be closed.
                    }
                    // Prevent the dialog from closing.
                    closeEvent.cancel = true;
                    // Execute the button onclick handler.
                    utils.js(button.click);
                },
            };
        }, false);

        // Show the dialog.
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
        // Delete the previous dialog factory.
        alertify.jaxon_dialog = undefined;
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
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.message The alert message
     *
     * @returns {void}
     */
    self.alert = ({ type, message }) => alertify.notify(message, xTypes[type] ?? xTypes.info);

    /**
     * Ask a confirm question to the user.
     *
     * @param {object} confirm The confirm parameters
     * @param {string} confirm.question The question to ask
     * @param {string} confirm.title The question title
     * @param {object} callback The confirm callbacks
     * @param {callback} callback.yes The function to call if the answer is yes
     * @param {callback=} callback.no The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => alertify
        .confirm(title ?? '&nbsp;', question, yesCb, noCb)
        .set('labels', { ok: labels.yes, cancel: labels.no });
});
