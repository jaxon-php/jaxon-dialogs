/**
 * Class: jaxon.dialog.lib.sweetalert
 */

jaxon.dialog.lib.register('sweetalert', (self, { labels, options = {} }) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

    const xTypes = {
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
    self.alert = (type, text, title) => {
        swal({ ...alertOptions, text, title: title ?? '', icon: xTypes[type] ?? xTypes.info });
    };

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => {
        swal({
            ...confirmOptions,
            icon: "warning",
            title,
            text: question,
            buttons: [labels.no, labels.yes],
        }).then((res) => {
            if(res)
                yesCallback();
            else if((noCallback))
                noCallback();
        });
    };
});
