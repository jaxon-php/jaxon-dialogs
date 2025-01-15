/**
 * Bootbox dialogs plugin
 * Class: jaxon.dialog.libs.bootbox
 */

jaxon.dialog.register('bootbox', (self, options, utils) => {
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
        dom: null,
        container: 'bootbox-container',
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
        let btnIndex = 1;
        const oButtons = {};
        buttons.forEach(({ title: label, class: className, click }) => {
            if (!utils.isObject(click)) {
                oButtons.cancel = {label, className: 'btn-danger' };
                return;
            }
            oButtons[`btn${btnIndex++}`] = {
                label,
                className,
                callback: !utils.isObject(click) ? undefined : () => {
                    utils.js(click);
                    return false; // Do not close the dialog.
                },
            };
        });
        dialog.dom = bootbox.dialog({
            ...modalOptions,
            ...options,
            title,
            message: content,
            buttons: oButtons,
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
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.message The alert message
     * @param {string} alert.title The alert title
     *
     * @returns {void}
     */
    self.alert = ({ type, message, title }) => {
        message = '<div class="alert alert-' + (xTypes[type] ?? xTypes.info) +
            '" style="margin-top:15px;margin-bottom:-15px;">' +
            (!message ? '' : '<strong>' + title + '</strong><br/>') + message + '</div>';
        bootbox.alert({ ...alertOptions, message });
    };

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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => bootbox.confirm({
        ...confirmOptions,
        title: title,
        message: question,
        buttons: {
            cancel: {label: labels.no},
            confirm: {label: labels.yes}
        },
        callback: (res) => {
            if(res)
                yesCb();
            else if((noCb))
                noCb();
        }
    });
});
