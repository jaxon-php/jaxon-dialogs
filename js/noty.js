/**
 * Class: jaxon.dialog.libs.noty
 */

jaxon.dialog.register('noty', (self, options) => {
    // Dialogs options
    const {
        labels,
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

    const xTypes = {
        success: 'success',
        info: 'information',
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
    self.alert = ({ type, message }) => {
        new Noty({
            ...alertOptions,
            text: message,
            type: xTypes[type] ?? xTypes.info,
            layout: 'topCenter',
            timeout: 5000,
        }).show();
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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => {
        const noty = new Noty({
            ...confirmOptions,
            theme: 'relax',
            text: question,
            layout: 'topCenter',
            buttons: [
                Noty.button(labels.yes, 'btn btn-success', () => {
                    noty.close();
                    yesCb();
                }, {'data-status': 'ok'}),
                Noty.button(labels.no, 'btn btn-error', () => {
                    noty.close();
                    noCb && noCb();
                }),
            ],
        });
        noty.show();
    };
});
