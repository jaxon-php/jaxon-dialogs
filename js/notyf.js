/**
 * Class: jaxon.dialog.libs.notyf
 */

jaxon.dom.ready(() => jaxon.dialog.register('notyf', (self, options) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
    } = options;

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'error',
    };

    /*
     * Todo: Define a custom icon for the warning and info messages,
     * and re-enable the default one on the success and error messages.
     */
    const notyf = new Notyf({
        duration: 5000,
        ripple: false,
        dismissible: true,
        position: { x: 'center', y: 'top' },
        types: [{
            type: 'success',
            className: 'notyf__toast--success',
            backgroundColor: '#3dc763',
            icon: false,
            // icon: {
            //     className: 'notyf__icon--success',
            //     tagName: 'i',
            // },
        }, {
            type: 'error',
            className: 'notyf__toast--error',
            backgroundColor: '#ed3d3d',
            icon: false,
            // icon: {
            //     className: 'notyf__icon--error',
            //     tagName: 'i',
            // },
        },{
            type: 'warning',
            background: '#FEBE10',
            icon: false,
            // icon: {
            //     className: 'material-icons',
            //     tagName: 'i',
            //     color: 'white',
            //     text: 'i',
            // },
        }, {
            type: 'info',
            background: '#318CE7',
            icon: false,
        }],
        ...alertOptions,
    });

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
        notyf.open({ type: xTypes[type] ?? xTypes.info, message });
    };
}));
