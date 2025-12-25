jaxon.config.requestURI = 'http://example.test/path';
jaxon.config.statusMessages = false;
jaxon.config.waitCursor = true;
jaxon.config.version = 'Jaxon 5.x';
jaxon.config.defaultMode = 'asynchronous';
jaxon.config.defaultMethod = 'POST';
jaxon.config.responseType = 'JSON';

jaxon.dom.ready(() => jaxon.processCustomAttrs());

jaxon.dom.ready(() => jaxon.dialog.config({"labels":{"confirm":{"yes":"Yes","no":"No"}},"defaults":{"modal":"alertify","alert":"alertify","confirm":"alertify"}}));

const jx = {
  rc: (name, method, parameters, options = {}) => jaxon.request({ type: 'class', name, method }, { parameters, ...options}),
  rf: (name, parameters, options = {}) => jaxon.request({ type: 'func', name }, { parameters, ...options}),
  c0: 'Dialog',
};

Dialog = {
  success: (...args) => jx.rc(jx.c0, 'success', args),
  info: (...args) => jx.rc(jx.c0, 'info', args),
  warning: (...args) => jx.rc(jx.c0, 'warning', args),
  error: (...args) => jx.rc(jx.c0, 'error', args),
  show: (...args) => jx.rc(jx.c0, 'show', args),
  showWith: (...args) => jx.rc(jx.c0, 'showWith', args),
  hide: (...args) => jx.rc(jx.c0, 'hide', args),
};

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
     * @param {function} cbDomElement A callback to call with the DOM element of the dialog content
     *
     * @returns {object}
     */
    self.show = ({ title, content, buttons, options }, cbDomElement) => {
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
                    dialog.cbDomElement(this.elements.content);
                    // Save the dialog instance locally.
                    dialog.instance = this;
                },
                build: function() {
                    // Pass the js content element to the callback.
                    // dialog.cbDomElement(this.elements.content);
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
        dialog.cbDomElement = cbDomElement;
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => alertify
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
     * @param {function} cbDomElement A callback to call with the DOM element of the dialog content
     *
     * @returns {object}
     */
    self.show = ({ title, content, buttons, options }, cbDomElement) => {
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
        cbDomElement(dialog.dom.get(0));
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => bootbox.confirm({
        ...confirmOptions,
        title: title,
        message: question,
        buttons: {
            cancel: {label: labels.no},
            confirm: {label: labels.yes}
        },
        callback: (res) => {
            res ? yesCb() : noCb();
        },
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => $.jAlert({
        ...confirmOptions,
        title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: labels.yes,
        denyBtnText: labels.no,
        onConfirm: yesCb,
        onDeny: noCb,
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => cuteAlert({
        ...confirmOptions,
        title,
        type: 'question',
        message: question,
        confirmText: labels.yes,
        cancelText: labels.no,
    }).then(res => {
        res === 'confirm' ? yesCb() : noCb();
    });
}));