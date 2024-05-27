/*********************************************************************************************
 *                                CNRS Data Manager Agents JS                                *
 ********************************************************************************************/

const viewSelector = document.querySelectorAll('.cnrs-dm-front-selector');
const allItems = document.querySelectorAll('.cnrs-dm-front-agent-container');
const listItems = document.querySelectorAll('.cnrs-dm-front-inline');
const gridItems = document.querySelectorAll('.cnrs-dm-front-card');
const allRows = document.querySelectorAll('.cnrs-dm-front-agent-wrapper');
const infoModals = document.querySelectorAll('.cnrs-dm-front-agent-info-wrapper');
const dataWrapper = document.querySelectorAll('.cnrs-dm-front-agent-info-data-container');
const parentContainers = document.querySelectorAll('.cnrs-dm-front-container');
const signPadButtons = document.querySelectorAll('.cnrs-dm-front-sign-pad-button');
const optionalInputs = document.querySelectorAll('.cnrs-dm-front-checkbox-label[data-option="option"]');
const missionFormSubmit = document.querySelector('#cnrs-dm-front-mission-form-submit-button');
const missionFormErrors = document.querySelector('#cnrs-dm-front-mission-form-errors');
const missionFormLoginWrapper = document.querySelector('#cnrs-dm-front-mission-form-login-wrapper');
const missionFormResetPwdLink = document.querySelector('#cnrs-dm-front-mission-form-reset-link');
const missionFormRGoToLoginLink = document.querySelector('#cnrs-dm-front-mission-form-login-link');
const loginForm = document.querySelector('.cnrs-dm-front-mission-form-login[data-action="login"]');
const resetForm = document.querySelector('.cnrs-dm-front-mission-form-login[data-action="reset"]');
const resetEmail = document.querySelector('input[type="email"][name="cnrs-dm-front-mission-form-reset-email"]');
const loginEmail = document.querySelector('input[type="email"][name="cnrs-dm-front-mission-form-login-email"]');
const resetEmailBtn = document.querySelector('#cnrs-dm-front-mission-form-submit-reset-btn');
const loginEmailBtn = document.querySelector('#cnrs-dm-front-mission-form-submit-login-btn');
const logoutBtn = document.querySelector('#cnrs-dm-front-mission-user-logout');
const tooltipBtns = document.querySelectorAll('.cnrs-dm-front-tooltip-btn');
const referneceDateInput = document.querySelector('#cnrs-dm-front-reference-input');
const referenceDateAlert = document.querySelector('#cnrs-dm-front-reference-alert');
let agentEmails = [];

window.addEventListener('load', function(){
    isMobile();
    displayContainers();
    dispatchWrapperListener();
    prepareMissionForm();
    resizeTooltips();
});

window.addEventListener('resize', function () {
    resizeTooltips();
});

window.addEventListener('click', function(e) {
    const target = e.target;
    closeMissionTooltips(target);
});

function isJson(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function prepareMissionForm() {
    if (typeof missionForm !== "undefined") {
        for (let i = 0; i < optionalInputs.length; i++) {
            optionalInputs[i].querySelector('input').oninput = function () {
                let textarea = this.parentElement.nextElementSibling;
                if (textarea && textarea.classList.contains('cnrs-dm-front-mission-form-opt-comment')) {
                    if (this.checked === true) {
                        textarea.required = true;
                    } else {
                        textarea.required = false;
                    }
                }
            }
        }
        for (let j = 0; j < signPadButtons.length; j++) {
            signPadButtons[j].onclick = function () {
                if (document.querySelector('#cnrs-dm-front-sign-pad-wrapper')) {
                    document.querySelector('#cnrs-dm-front-sign-pad-wrapper').remove();
                }
                const originals = this.parentElement.querySelector('input[type="hidden"]').value;
                let values = isJson(originals) ? JSON.parse(originals) : {};

                let html = `<div id="cnrs-dm-front-sign-pad-wrapper" data-index="${j}">`;
                let labels = this.dataset.labels.split(';');
                html += `<div id="cnrs-dm-front-sign-pad-modal" data-index="${j}">`;
                for (let k = 0; k < labels.length; k++) {
                    html += `<label class="cnrs-dm-front-sign-pad-modal-label required">`;
                    html += `<span>${labels[k]}</span>`;
                    html += `<input required type="text" spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-signs-${this.dataset.index}-pad-label-${k}" value="${values[labels[k]] ?? ''}">`;
                    html += `</label>`;
                }
                html += `<label class="cnrs-dm-front-sign-pad-modal-label required">`;
                html += `<span>${this.dataset.sign}</span>`;
                html += `<input type="hidden" name="cnrs-dm-front-mission-form-element-signs-${this.dataset.index}-sign-${j}">`;
                html += `</label>`;
                html += `<canvas id="cnrs-dm-front-sign-pad" data-index="${j}"></canvas>`;
                html += '<div id="cnrs-dm-front-signs-buttons-wrapper">';
                html += `<button type="button" class="cnrs-dm-front-btn-simple" data-action="clear">${this.dataset.clear}</button>`;
                html += `<button type="button" class="cnrs-dm-front-btn-simple" data-action="cancel">${this.dataset.cancel}</button>`;
                html += `<button ${values.sign !== undefined ? '' : 'disabled'} type="button" class="cnrs-dm-front-btn-simple" data-action="save">${this.dataset.save}</button>`;
                html += '</div>';
                html += '</div>';
                html += '</div>';
                document.body.insertAdjacentHTML('beforeend', html);
                const canvas = document.querySelector('#cnrs-dm-front-sign-pad');
                const refBtn = this;
                const clearBtn = document.querySelector('.cnrs-dm-front-btn-simple[data-action="clear"]');
                const cancelBtn = document.querySelector('.cnrs-dm-front-btn-simple[data-action="cancel"]');
                const saveBtn = document.querySelector('.cnrs-dm-front-btn-simple[data-action="save"]');
                let signatureOK = false;
                const signaturePad = new SignaturePad(canvas, {
                    penColor: "#000000"
                });
                if (values.sign !== undefined) {
                    signaturePad.fromDataURL(values.sign);
                    signatureOK = true;
                }
                let inputs = document.querySelectorAll('.cnrs-dm-front-sign-pad-modal-label.required input[type="text"]');
                for (let i = 0; i < inputs.length; i++) {
                    values[inputs[i].previousElementSibling.innerHTML] = values[inputs[i].previousElementSibling.innerHTML] !== undefined && values[inputs[i].previousElementSibling.innerHTML].trim().length === 0
                        ? 'error'
                        : values[inputs[i].previousElementSibling.innerHTML];
                    inputs[i].oninput = function() {
                        let label = this.previousElementSibling.innerHTML;
                        if (this.value.length < 1) {
                            values[label] = 'error';
                        } else {
                            values[label] = this.value;
                        }
                        saveBtn.disabled = false;
                        for (const valuesKey in values) {
                            if (values[valuesKey] === 'error') {
                                saveBtn.disabled = true;
                                break;
                            }
                        }
                        if (saveBtn.disabled === false && signatureOK === false) {
                            saveBtn.disabled = true;
                        }
                    }
                }
                clearBtn.onclick = function () {
                    signaturePad.clear();
                    signatureOK = false;
                    saveBtn.disabled = false;
                    for (const valuesKey in values) {
                        if (values[valuesKey] === 'error') {
                            saveBtn.disabled = true;
                            break;
                        }
                    }
                    if (saveBtn.disabled === false && signatureOK === false) {
                        saveBtn.disabled = true;
                    }
                }
                cancelBtn.onclick = function () {
                    document.querySelector('#cnrs-dm-front-sign-pad-wrapper').classList.remove('display');
                    setTimeout(function () {
                        document.querySelector('#cnrs-dm-front-sign-pad-wrapper').remove();
                    }, 250);
                }
                saveBtn.onclick = function () {
                    if (!signaturePad.isEmpty()) {
                        values.sign = signaturePad.toDataURL();
                        let json = JSON.stringify(values);
                        const ref = document.querySelector('input[name="cnrs-dm-front-mission-form-element-signs-' + refBtn.dataset.iteration + '-pad-' + refBtn.dataset.index + '"]');
                        ref.value = json;
                        const constainer = document.querySelector('#cnrs-dm-front-sign-pad-preview-' + refBtn.dataset.iteration + '-pad-' + refBtn.dataset.index + '');
                        let containerHtml = '';
                        for (const valuesKey in values) {
                            if (valuesKey !== 'sign') {
                                containerHtml += `<span>${values[valuesKey]}</span>`;
                            }
                        }
                        containerHtml += '<img src="' + values.sign + '" alt="signature" class="cnrs-dm-front-sign-preview-img">';
                        constainer.innerHTML = containerHtml;
                        document.querySelector('#cnrs-dm-front-sign-pad-wrapper').classList.remove('display');
                        setTimeout(function () {
                            document.querySelector('#cnrs-dm-front-sign-pad-wrapper').remove();
                        }, 250);
                    }
                }
                signaturePad.addEventListener("beginStroke", () => {
                    signatureOK = true;
                    saveBtn.disabled = false;
                    for (const valuesKey in values) {
                        if (values[valuesKey] === 'error') {
                            saveBtn.disabled = true;
                            break;
                        }
                    }
                    if (saveBtn.disabled === false && signatureOK === false) {
                        saveBtn.disabled = true;
                    }
                });
                setTimeout(function (){
                    document.querySelector('#cnrs-dm-front-sign-pad-wrapper').classList.add('display');
                }, 50);
            }
        }

        if (referneceDateInput && referenceDateAlert && daysLimit && daysLimitAlert) {
            referneceDateInput.addEventListener('input', function (event) {
                let count = daysLimit;
                const mission = new Date(this.value);
                let limit = new Date();
                while (count > 0) {
                    limit.setDate(limit.getDate() + 1);
                    if (limit.getDay() !== 0 && limit.getDay() !== 6) {
                        count--;
                    }
                }
                if (!isNaN(new Date(limit)) && mission < limit) {
                    referenceDateAlert.innerHTML = daysLimitAlert;
                    referenceDateAlert.classList.add('show');
                } else {
                    referenceDateAlert.innerHTML = '';
                    referenceDateAlert.classList.remove('show');
                }
            });
        }
    }

    if (missionFormSubmit) {
        missionFormSubmit.onclick = function (e) {
            e.preventDefault();
            let errors = [];
            const toControl = document.querySelectorAll('.cnrs-dm-front-mission-form-element[data-state="light"]');
            for (let i = 0; i < toControl.length; i++) {
                const container = toControl[i];
                const type = container.dataset.type;
                const messages = JSON.parse(missionFormErrors.dataset.messages);
                if (type === 'input') {
                    const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-input-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    if (elmt.value.trim().length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                    }
                } else if (type === 'number') {
                    const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-number-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    if (elmt.value.trim().length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                    } else if (isNaN(elmt.value)) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.number);
                    } else if (parseInt(elmt.value) < 0) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.unsigned);
                    }
                } else if (type === 'date' || type === 'time' || type === 'datetime') {
                    const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-' + type + '-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    if (elmt.value.trim().length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                    }
                } else if (type === 'textarea') {
                    const elmt = container.querySelector('textarea[name^="cnrs-dm-front-mission-form-element-textarea-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    if (elmt.value.trim().length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                    }
                } else if (type === 'radio' || type === 'radio-convention') {
                    const radioInputs = type === 'radio'
                        ? container.querySelectorAll('input[name^="cnrs-dm-front-mission-form-element-radio-"]')
                        : container.querySelectorAll('input[name="cnrs-dm-front-convention"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    let radioChecked = [];
                    for (let j = 0; j < radioInputs.length; j++) {
                        if (radioInputs[j].checked === true) {
                            radioChecked.push(j);
                        }
                    }
                    if (radioChecked.length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.radio);
                    }
                    const optComments = container.querySelectorAll('.cnrs-dm-front-mission-form-opt-comment:required');
                    for (let j = 0; j < optComments.length; j++) {
                        if (optComments[j].value.trim().length < 1) {
                            const opt = optComments[j].previousElementSibling.querySelector('.text').innerHTML;
                            errors.push('<b>' + label + ' ' + opt + '</b>&nbsp;' + messages.option);
                        }
                    }
                } else if (type === 'checkbox') {
                    const checkboxInputs = container.querySelectorAll('input[name^="cnrs-dm-front-mission-form-element-checkbox-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').innerHTML;
                    let checkboxChecked = [];
                    for (let j = 0; j < checkboxInputs.length; j++) {
                        if (checkboxInputs[j].checked === true) {
                            checkboxChecked.push(j);
                        }
                    }
                    if (checkboxChecked.length < 1) {
                        errors.push('<b>' + label + '</b>&nbsp;' + messages.checkbox);
                    }
                    const optComments = container.querySelectorAll('.cnrs-dm-front-mission-form-opt-comment:required');
                    for (let j = 0; j < optComments.length; j++) {
                        if (optComments[j].value.trim().length < 1) {
                            const opt = optComments[j].previousElementSibling.querySelector('.checkbox__text-wrapper').innerHTML;
                            errors.push('<b>' + label + ' ' + opt + '</b>&nbsp;' + messages.option);
                        }
                    }
                } else if (type === 'signs') {
                    const inputs = container.querySelectorAll('input[name^="cnrs-dm-front-mission-form-element-signs-"]');
                    const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.error;
                    for (let j = 0; j < inputs.length; j++) {
                        if (isJson(inputs[j].value)) {
                            const json = JSON.parse(inputs[j].value);
                            if (json.sign === undefined) {
                                errors.push('<b>' + label + ' ' + (j+1) + '</b>&nbsp;' + messages.signs);
                            }
                        } else {
                            errors.push('<b>' + label + ' ' + (j+1) + '</b>&nbsp;' + messages.signs);
                        }
                    }
                }
            }
            missionFormErrors.innerHTML = '';
            if (errors.length > 0) {
                let html = '';
                for (let i = 0; i < errors.length; i++) {
                    html += `<li>${errors[i]}</li>`;
                }
                missionFormErrors.insertAdjacentHTML('beforeend', html);
            } else {
                missionFormSubmit.closest('form').submit();
            }
        }
    }

    if (typeof xmlAgents !== "undefined") {
        for (let i = 0; i < xmlAgents.length; i++) {
            const agent = xmlAgents[i];
            if (typeof agent.email_pro === "string" && agent.email_pro.length > 0) {
                agentEmails.push(agent.email_pro);
            }
        }
    }

    if (missionFormLoginWrapper) {
        missionFormResetPwdLink.onclick = function () {
            loginForm.classList.add('hide');
            resetForm.classList.remove('hide');
            const resetLink = document.querySelector('.cnrs-dm-front-mission-form-confirm-reset');
            if (resetLink) {
                resetLink.remove();
            }
        }
        missionFormRGoToLoginLink.onclick = function () {
            loginForm.classList.remove('hide');
            resetForm.classList.add('hide');
        }
    }

    if (resetEmail) {
        resetEmail.oninput = function() {
            resetEmailBtn.disabled = !agentEmails.includes(this.value);
            this.style.color = !agentEmails.includes(this.value) ? '#ff5c33' : 'inherit';

            loginEmail.value = this.value;
            loginEmailBtn.disabled = !agentEmails.includes(this.value);
            loginEmail.style.color = !agentEmails.includes(this.value) ? '#ff5c33' : 'inherit';
        }
    }

    if (loginEmail) {
        loginEmail.oninput = function() {
            loginEmailBtn.disabled = !agentEmails.includes(this.value);
            this.style.color = !agentEmails.includes(this.value) ? '#ff5c33' : 'inherit';

            resetEmail.value = this.value;
            resetEmailBtn.disabled = !agentEmails.includes(this.value);
            resetEmail.style.color = !agentEmails.includes(this.value) ? '#ff5c33' : 'inherit';
        }
    }

    if (logoutBtn) {
        logoutBtn.onclick = function() {
            document.cookie = "wp-cnrs-dm=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            const action = window.location.pathname;
            window.location.href = action;
        }
    }

    for (let i = 0; i < tooltipBtns.length; i++) {
        tooltipBtns[i].onclick = function () {
            const tooltipText = this.nextElementSibling;
            if (tooltipText.classList.contains('show')) {
                tooltipText.classList.remove('show');
                this.classList.remove('show-tooltip');
            } else {
                for (let j = 0; j < tooltipBtns.length; j++) {
                    const tooltipTextAll = tooltipBtns[j].nextElementSibling;
                    tooltipTextAll.classList.remove('show');
                    tooltipBtns[j].classList.remove('show-tooltip');
                }
                tooltipText.classList.add('show');
                this.classList.add('show-tooltip');
            }
        }
    }
}

function closeMissionTooltips(target) {
    if (!target.classList.contains('cnrs-dm-front-tooltip-text')
        && !target.closest('.cnrs-dm-front-tooltip-text')
        && !target.classList.contains('cnrs-dm-front-tooltip-btn')
        && !target.closest('.cnrs-dm-front-tooltip-btn')
    ) {
        for (let i = 0; i < tooltipBtns.length; i++) {
            const tooltipTextAll = tooltipBtns[i].nextElementSibling;
            tooltipTextAll.classList.remove('show');
            tooltipBtns[i].classList.remove('show-tooltip');
        }
    }
}

function resizeTooltips() {

    for (let i = 0; i < tooltipBtns.length; i++) {
        const parent = tooltipBtns[i].closest('.cnrs-dm-front-mission-form-element');
        const parentWidth = parent.offsetWidth;
        const label = tooltipBtns[i].closest('.cnrs-dm-front-mission-form-element-label');
        const labelWidth = label.scrollWidth + 16;
        const text = tooltipBtns[i].nextElementSibling;
        text.style.maxWidth = parentWidth + 'px';
        text.style.minWidth = labelWidth + 'px';
    }
}

function dispatchWrapperListener() {

    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].addEventListener('click', function (){
            const action = this.dataset.action;
            clearSelectors();
            this.classList.add('selected');
            if (action === 'list') {
                for (let i = 0; i < listItems.length; i++) {
                    listItems[i].classList.add('selected');
                }
                for (let i = 0; i < allRows.length; i++) {
                    allRows[i].classList.add('cnrs-dm-front-agent-wrapper-list');
                    allRows[i].classList.remove('cnrs-dm-front-agent-wrapper-grid');
                }
            } else {
                for (let i = 0; i < gridItems.length; i++) {
                    gridItems[i].classList.add('selected');
                }
                for (let i = 0; i < allRows.length; i++) {
                    allRows[i].classList.remove('cnrs-dm-front-agent-wrapper-list');
                    allRows[i].classList.add('cnrs-dm-front-agent-wrapper-grid');
                }
            }
        });
    }

    for (let i = 0; i < allRows.length; i++) {
        allRows[i].onclick = function (e){
            const target = e.target;
            if (!target.classList.contains('cnrs-dm-front-membership-item')) {
                let element = this.querySelector('.cnrs-dm-front-agent-info-wrapper').cloneNode(true);
                document.body.appendChild(element);
                setTimeout(function () {
                    document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').classList.add('show');
                    document.querySelector('body > .cnrs-dm-front-agent-info-wrapper .cnrs-dm-close-info-container').onclick = function () {
                        document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').classList.remove('show');
                        setTimeout(function () {
                            document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').remove();
                        }, 550);
                    }
                }, 50);
            }
        };
    }
}
function clearSelectors() {
    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].classList.remove('selected');
    }
    for (let i = 0; i < allItems.length; i++) {
        allItems[i].classList.remove('selected');
    }
}

function isMobile() {
    const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
    if (regex.test(navigator.userAgent)) {
        for (let i = 0; i < dataWrapper.length; i++) {
            dataWrapper[i].classList.add('mobile-version');
        }
    }
}

function displayContainers() {
    for (let i = 0; i < parentContainers.length; i++) {
        parentContainers[i].style.display = 'block';
    }
}
