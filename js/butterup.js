/**
 * Class: jaxon.dialog.libs.butterup
 */

jaxon.dom.ready(() => jaxon.dialog.register('butterup', (self, options) => {
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
        butterup.toast({
            type: xTypes[type] ?? xTypes.info,
            title,
            message,
            location: 'top-center',
            icon: true,
            dismissable: true,
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
        const toastOptions = {
            id: '', // The id of the confirm toast.
            life: butterup.options.toastLife, // Save the toastLife value.
        };
        // Set the toast life to a higher value, so the confirm dialog is not dismissed too early.
        // Todo: disable the dismissable timeout.
        butterup.options.toastLife = 60000;

        butterup.toast({
            title,
            message: question,
            location: 'top-center',
            icon: false,
            dismissable: false,
            ...confirmOptions,
            onRender: (toast) => {
                // Save the id of the confirm toast.
                toastOptions.id = toast.id;
            },
            primaryButton: {
                text: labels.yes,
                onClick: () => {
                    // Close the confirm toast.
                    butterup.despawnToast(toastOptions.id);
                    yesCb();
                },
            },
            secondaryButton: {
                text: labels.no,
                onClick: () => {
                    // Close the confirm toast.
                    butterup.despawnToast(toastOptions.id);
                    noCb();
                },
            },
        });

        // Restore the initial toastLife value.
        butterup.options.toastLife = toastOptions.life;
    };
}));
