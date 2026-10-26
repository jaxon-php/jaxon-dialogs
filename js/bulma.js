/*
 * Bulma dialogs plugin
 * Class: jaxon.dialog.libs.bulma
 */

jaxon.dom.ready(() => jaxon.dialog.register('bulma', (self, options, utils) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
    } = options;

    const modal = {
        instance: null,
        dialogId: 'bulma-modal-container',
        closeClass: 'bulma-modal-close',
    };
    const btnClasses = {
        primary: 'button is-primary',
        danger: 'button is-danger',
        default: 'button',
    };

    // Create the DOM element for the modal
    if(!utils.jq(`#${modal.dialogId}`).length)
    {
        utils.jq('body').append(`<div id="${modal.dialogId}" class="modal"></div>`);
    }

    modal.instance = document.getElementById(modal.dialogId);
    modal.closeSelector = `#${modal.dialogId} .${modal.closeClass}`;

    const encodeHandler = (click) => JSON.stringify(click)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const getHtml = (title, content, buttons) => {
        buttons = buttons
            .map(({ title, class: btnClass, click }) => {
                btnClass = btnClasses[btnClass] ?? btnClasses.default;
                const attrs = click === 'close' ?
                    `class="${btnClass} ${modal.closeClass}"` :
                    `class="${btnClass}" jxn-click="${encodeHandler(click)}"`;
                return `<button type="button" ${attrs}>${title}</button>`;
            })
            .reduce((html, button) => html + button, '');

        return `
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">${title}</p>
      <button class="delete ${modal.closeClass}" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      ${content !== '' ? content : '&nbsp;'}
    </section>
    <footer class="modal-card-foot">
      <div class="buttons">
        ${buttons}
      </div>
    </footer>
  </div>
`;
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
        self.hide();

        modal.instance.innerHTML = getHtml(title, content, buttons);
        const btns = document.querySelectorAll(modal.closeSelector);
        Array.from(btns).forEach((btn) => btn.addEventListener("click", self.hide));
        // Pass the js content element to the callback.
        cbDomElement(modal.instance);
        // Show the modal.
        modal.instance.classList.add("is-active");
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => modal.instance?.classList.remove("is-active");
}));
