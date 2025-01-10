/**
 * Bootbox dialogs plugin
 * Class: jaxon.dialog.lib.bootbox
 */

jaxon.dialog.lib.register('bootbox', (self, { dom, js, types, jq, labels, options = {} }) => {
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
        dom: null,
        container: 'bootbox-container',
    };

    /**
     * Show the modal dialog
     *
     * @param {string} title The dialog title
     * @param {string} content The dialog HTML content
     * @param {array} buttons The dialog buttons
     * @param {array} options The dialog options
     * @param {int} options.width The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = (title, content, buttons, options, jsElement) => {
        dialog.dom = bootbox.dialog({
            ...modalOptions,
            ...options,
            title,
            message: content,
            buttons: buttons
                .map(({ title: label, class: btnClass, click }) => ({
                    label,
                    btnClass: btnClass,
                    callback: !types.isObject(click) ? undefined : () => {
                        js.execExpr(click);
                        return false; // Do not close the dialog.
                    },
                })),
        });
        // Pass the js content element to the callback.
        jsElement(dialog.dom.get(0));
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if ((dialog.dom)) {
            dialog.dom.modal('hide');
            dialog.dom = null;
        }
    };

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'danger',
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
    self.alert = (type, text, title) => {
        const message = '<div class="alert alert-' + (xTypes[type] ?? xTypes.info) +
            '" style="margin-top:15px;margin-bottom:-15px;">' +
            (!title ? '' : '<strong>' + title + '</strong><br/>') + text + '</div>';
        bootbox.alert({ ...alertOptions, message });
    };

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => bootbox.confirm({
        ...confirmOptions,
        title: title,
        message: question,
        buttons: {
            cancel: {label: labels.no},
            confirm: {label: labels.yes}
        },
        callback: (res) => {
            if(res)
                yesCallback();
            else if((noCallback))
                noCallback();
        }
    });
});
