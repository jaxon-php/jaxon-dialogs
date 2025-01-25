/*
 * Alertify dialogs plugin
 * Class: jaxon.dialog.libs.alertify
 */

jaxon.dom.ready(() => jaxon.dialog.register('alertify', (self, options, utils) => {
    // Dialogs options
    const {
        labels,
        modal: modalOptions = {},
        // alert: alertOptions = {},
        // confirm: confirmOptions = {},
    } = options;

    /**
     * @var {object}
     */
    const dialog = {
        instance: null,
        name: 'jaxon_dialog',
        index: 1,
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
        /*
         * Warning: a new dialog factory will be registered each time a dialog is displayed.
         * Todo: Free the unused factories.
         */
        const dialogName = `${dialog.name}${dialog.index++}`;
        // Create the dialog factory.
        alertify.dialog(dialogName, function factory() {
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
        alertify[dialogName](content);
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
}));


/**
 * Bootbox dialogs plugin
 * Class: jaxon.dialog.libs.bootbox
 */

jaxon.dom.ready(() => jaxon.dialog.register('bootbox', (self, options, utils) => {
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
                callback: () => {
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
}));


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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => BootstrapDialog.confirm({
        ...confirmOptions,
        title,
        message: question,
        btnOKLabel: labels.yes,
        btnCancelLabel: labels.no,
        callback: (res) => {
            if(res)
                yesCb();
            else if(noCb !== undefined)
                noCb();
        }
    });
}));


/**
 * Class: jaxon.dialog.libs.cute
 */

jaxon.dom.ready(() => jaxon.dialog.register('cute', (self, options) => {
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
     * @param {string} alert.message The alert message
     * @param {string} alert.title The alert title
     *
     * @returns {void}
     */
    self.alert = ({ type, message, title }) => cuteAlert({
        ...alertOptions,
        message,
        title: title ?? '&nbsp;',
        type: xTypes[type] ?? xTypes.info,
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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => cuteAlert({
        ...confirmOptions,
        title,
        type: 'question',
        message: question,
        confirmText: labels.yes,
        cancelText: labels.no,
    }).then(e => {
        if(e === 'confirm')
        {
            yesCb();
        }
        else if((noCb))
        {
            noCb();
        }
    });
}));


/**
 * Class: jaxon.dialog.libs.jalert
 */

jaxon.dom.ready(() => jaxon.dialog.register('jalert', (self, options) => {
    // Dialogs options
    const {
        labels,
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

    const xTypes = {
        success: 'green',
        info: 'blue',
        warning: 'yellow',
        error: 'red',
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
    self.alert = ({ type, message, title }) => $.jAlert({
        ...alertOptions,
        content: message,
        title,
        theme: xTypes[type] ?? xTypes.info,
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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => $.jAlert({
        ...confirmOptions,
        title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: labels.yes,
        denyBtnText: labels.no,
        onConfirm: yesCb,
        onDeny: noCb ?? (() => {}),
    });
}));


/**
 * Class: jaxon.dialog.libs.jconfirm
 */

jaxon.dom.ready(() => jaxon.dialog.register('jconfirm', (self, options, utils) => {
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
        self.hide();

        // Add buttons
        const xButtons = {};
        buttons.forEach(({ title: text, class: btnClass, click }, btnIndex) => {
            const handler = !utils.isObject(click) ? () => self.hide() :
                () => { utils.js(click); return false; };
            xButtons['btn' + btnIndex] = { text, btnClass, action: handler };
        });

        dialog.dom = $.confirm({
            ...modalOptions,
            title,
            content,
            ...options,
            closeIcon: true,
            useBootstrap: true,
            boxWidth: 600,
            buttons: xButtons,
            // Pass the js content element to the callback.
            onContentReady: () => jsElement(dialog.dom.$jconfirmBox.get(0)),
        });
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        !dialog.dom || dialog.dom.close();
        dialog.dom = null;
    };

    const xTypes = {
        success: 'green',
        info: 'blue',
        warning: 'orange',
        error: 'red',
    };

    const xIcons = {
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
     * @param {string} alert.message The alert message
     * @param {string} alert.title The alert title
     *
     * @returns {void}
     */
    self.alert = ({ type, message, title }) => $.alert({
        ...alertOptions,
        content: message,
        title,
        type: xTypes[type] ?? xTypes.info,
        icon: 'fa fa-' + (xIcons[type] ?? xIcons.info),
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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => $.confirm({
        ...confirmOptions,
        title,
        content: question,
        buttons: {
            yes: {
                btnClass: "btn-blue",
                text: labels.yes,
                action: yesCb,
            },
            no: {
                text: labels.no,
                action: noCb ?? (() => {}),
            },
        },
    });
}));


/**
 * Class: jaxon.dialog.libs.notify
 */

jaxon.dom.ready(() => jaxon.dialog.register('notify', (self, options) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
    } = options;

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warn',
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
        $.notify(message, {
            ...alertOptions,
            className: xTypes[type] ?? xTypes.info,
            position: "top center",
        });
    };
}));


/**
 * Class: jaxon.dialog.libs.noty
 */

jaxon.dom.ready(() => jaxon.dialog.register('noty', (self, options) => {
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
}));


/**
 * Class: jaxon.dialog.libs.sweetalert
 */

jaxon.dom.ready(() => jaxon.dialog.register('sweetalert', (self, options) => {
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
     * @param {string} alert.message The alert message
     * @param {string} alert.title The alert title
     *
     * @returns {void}
     */
    self.alert = ({ type, message, title }) => swal({
        ...alertOptions,
        text: message,
        title: title ?? '',
        icon: xTypes[type] ?? xTypes.info,
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
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb }) => swal({
        ...confirmOptions,
        icon: "warning",
        title,
        text: question,
        buttons: [labels.no, labels.yes],
    }).then((res) => {
        if(res)
            yesCb();
        else if((noCb))
            noCb();
    });
}));


/**
 * Class: jaxon.dialog.libs.tingle
 */

jaxon.dom.ready(() => jaxon.dialog.register('tingle', (self, options, utils) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
    } = options;

    /**
     * @var {object}
     */
    const dialog = {
        dom: null,
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
        self.hide();
        dialog.dom = new tingle.modal({
            ...modalOptions,
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            ...options,
        });
        // Set content
        dialog.dom.setContent('<h2>' + title + '</h2>' + content);
        // Add buttons
        buttons.forEach(({ title, class: btnClass, click }) => {
            const handler = utils.isObject(click) ?
                () => utils.js(click) : () => self.hide();
            dialog.dom.addFooterBtn(title, btnClass, handler);
        });
        // Open the modal
        dialog.dom.open();
        // Pass the js content element to the callback.
        jsElement(dialog.dom.modalBox);
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if(!dialog.dom)
        {
            return;
        }
        // Close an destroy the modal
        dialog.dom.close();
        dialog.dom.destroy();
        dialog.dom = null;
    };
}));


/**
 * Class: jaxon.dialog.libs.toastr
 */

jaxon.dom.ready(() => jaxon.dialog.register('toastr', (self, options) => {
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
        const func = xTypes[type] ?? xTypes.info;
        toastr[func](message, title, alertOptions);
    };
}));
