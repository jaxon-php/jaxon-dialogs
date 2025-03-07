/**
 * Class: jaxon.dialog.libs.izitoast
 */

jaxon.dom.ready(() => jaxon.dialog.register('izitoast', (self, options) => {
    // Dialogs options
    const {
        labels,
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
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.title The alert title
     * @param {string} alert.message The alert message
     *
     * @returns {void}
     */
    self.alert = ({ type, title, message }) => {
        type = xTypes[type] ?? xTypes.info;
        iziToast[type]({
            title,
            message,
            position: 'topCenter',
            ...alertOptions,
        });
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => {
        iziToast.question({
            timeout: 20000,
            close: false,
            overlay: true,
            displayMode: 'once',
            id: 'question',
            zindex: 999,
            title,
            message: question,
            position: 'topCenter',
            ...confirmOptions,
            buttons: [[
                `<button><b>${labels.yes}</b></button>`,
                (instance, toast) => {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    yesCb();
                },
                true,
            ], [
                `<button>${labels.no}</button>`,
                (instance, toast) => {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    noCb();
                },
            ]],
        });
    };
}));
