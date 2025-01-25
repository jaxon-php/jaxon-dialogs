/*
 * Bootstrap dialogs plugin
 * Class: jaxon.dialog.libs.bootstrap
 */

jaxon.dom.ready(() => jaxon.dialog.register('bootstrap', (self, options, utils) => {
    // Dialogs options
    const {
        labels,
        modal: modalOptions = {},
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

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
        dialog = BootstrapDialog.show({
            ...modalOptions,
            ...options,
            title,
            message: content,
            buttons: buttons.map(({ title: label, class: cssClass, click }) => {
                const handler = utils.isObject(click) ?
                    () => utils.js(click) : dialog => dialog.close();
                return {
                    label,
                    cssClass,
                    action: handler,
                };
            }),
        });
        // Pass the js content element to the callback.
        jsElement(dialog.$modal.get(0));
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => BootstrapDialog.closeAll();

    const xTypes = {
        success: BootstrapDialog.TYPE_SUCCESS,
        info: BootstrapDialog.TYPE_INFO,
        warning: BootstrapDialog.TYPE_WARNING,
        error: BootstrapDialog.TYPE_DANGER,
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
    self.alert = ({ type, message, title }) => BootstrapDialog.alert({
        ...alertOptions,
        title,
        type: xTypes[type] ?? xTypes.info,
        message,
    });

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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb = () => {} }) => BootstrapDialog.confirm({
        ...confirmOptions,
        title,
        message: question,
        btnOKLabel: labels.yes,
        btnCancelLabel: labels.no,
        callback: (res) => {
            res ? yesCb() : noCb();
        },
    });
}));
