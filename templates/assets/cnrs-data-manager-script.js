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
const optionalRadioInputs = document.querySelectorAll('.cnrs-dm-front-radio-label');
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
const observationButtons = document.querySelectorAll('.cnrs-dm-front-revision-observation');
const revisionSubmitButton = document.querySelector('#cnrs-dm-front-revision-form-submit-button');
const missionFormDiv = document.querySelector('.cnrs-dm-mission-form');
const chooseDestWrapper = document.querySelector('#cnrs-dm-front-mission-dest-button-container');
const chooseDestMissionBtns = document.querySelectorAll('.cnrs-dm-front-btn-choose-dest');
const intlParagraph = document.querySelector('#cnrs-dm-front-mission-intl');
const missionHTMLForm = document.querySelector('#cnrs-dm-front-mission-form-wrapper');
const desInput = document.querySelector('input[name="cnrs-dm-front-mission-intl"]');
const hiddenConventionInput = document.querySelector('#cnrs-dm-front-convention-id');
const textConventionInput = document.querySelector('#cnrs-dm-front-convention-text');
const conventionList = document.querySelector('#cnrs-dm-front-conventions-list');
const conventionsLi = document.querySelectorAll('.cnrs-dm-front-convention');
const publicationsForm = document.querySelector('#cnrs-dm-front-filters-publications-form');
const publicationsLoader = document.querySelector('#cnrs-dm-front-publication-loader-wrapper');
const publicationsList = document.querySelector('#cnrs-dm-front-publications-list');
let agentEmails = [];

window.addEventListener('load', function(){
    isMobile();
    displayContainers();
    dispatchWrapperListener();
    prepareMissionForm();
    resizeTooltips();
    setPublicationsListener();
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

function setPublicationsListener() {

    if (publicationsForm) {
        publicationsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            publicationsLoader.classList.remove('hide');
            const search = publicationsForm.querySelector('input[name="cdm-search"]').value;
            const author = publicationsForm.querySelector('select[name="cdm-author"]').value;
            const date = publicationsForm.querySelector('select[name="cdm-date"]').value;
            const type = publicationsForm.querySelector('select[name="cdm-type"]').value;
            const guardianship = publicationsForm.querySelector('select[name="cdm-guardianship"]').value;
            const category = publicationsForm.querySelector('select[name="cdm-category"]').value;
            const formData = new FormData();
            const url = '/wp-admin/admin-ajax.php';
            formData.append('action', 'search_publications');
            formData.append('cdm-search', search);
            formData.append('cdm-author', author);
            formData.append('cdm-date', date);
            formData.append('cdm-type', type);
            formData.append('cdm-guardianship', guardianship);
            formData.append('cdm-category', category);
            let queryString = new URLSearchParams(formData)
                .toString()
                .replace('action=search_publications&', '?');
            const nextURL = window.location.protocol + '//' + window.location.hostname + window.location.pathname + queryString;
            const nextTitle = document.title;
            const nextState = { additionalInformation: 'Updated the URL with JS' };
            window.history.pushState(nextState, nextTitle, nextURL);
            const options = {
                method: 'POST',
                body: formData,
            };
            fetch(url, options)
                .then(
                    response => response.json()
                ).then(
                success => handlePublicationsResult(success.data)
            ).catch(
                error => handlePublicationsResult({error: error, data: null})
            );
        })
    }
}

function handlePublicationsResult(response) {
    if (response.error === null) {
        publicationsList.innerHTML = response.data;
    } else {
        console.warn(response.error);
    }
    publicationsLoader.classList.add('hide');
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
        for (let i = 0; i < optionalRadioInputs.length; i++) {
            optionalRadioInputs[i].querySelector('input').oninput = function () {
                let textarea = this.parentElement.nextElementSibling;
                if (textarea && textarea.classList.contains('cnrs-dm-front-mission-form-opt-comment') && this.checked === true) {
                    textarea.required = true;
                } else {
                    let textarea2 = this.closest('.cnrs-dm-front-mission-form-element');
                    if (textarea2 && textarea2.querySelector('textarea')) {
                        textarea2.querySelector('textarea').required = false;
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

        if (referneceDateInput && referenceDateAlert && daysLimit && daysLimitAlert && monthLimit && monthLimitAlert) {
            referneceDateInput.addEventListener('input', function (event) {
                if (isInternational !== null) {
                    let count = isInternational === true ? monthLimit : daysLimit;
                    const mission = new Date(this.value);
                    let limit = new Date();
                    while (count > 0) {
                        limit.setDate(limit.getDate() + 1);
                        if (limit.getDay() !== 0 && limit.getDay() !== 6) {
                            count--;
                        }
                    }
                    if (!isNaN(new Date(limit)) && mission < limit) {
                        referenceDateAlert.innerHTML = isInternational === true ? monthLimitAlert : daysLimitAlert;
                        referenceDateAlert.classList.add('show');
                    } else {
                        referenceDateAlert.innerHTML = '';
                        referenceDateAlert.classList.remove('show');
                    }
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
                if (!toControl[i].classList.contains('hidden-by-toggle')) {
                    const container = toControl[i];
                    const type = container.dataset.type;
                    const messages = JSON.parse(missionFormErrors.dataset.messages);
                    if (type === 'input') {
                        const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-input-"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        if (elmt && elmt.readOnly === false && elmt.value.trim().length < 1) {
                            if (label.trim().length < 1) {
                                errors.push(messages.noLabel);
                            } else {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                            }
                        }
                    } else if (type === 'number') {
                        const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-number-"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        if (elmt && elmt.readOnly === false) {
                            if (elmt.value.trim().length < 1) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                            } else if (isNaN(elmt.value)) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.number);
                            } else if (parseInt(elmt.value) < 0) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.unsigned);
                            }
                        }
                    } else if (type === 'date' || type === 'time' || type === 'datetime') {
                        const elmt = container.querySelector('input[name^="cnrs-dm-front-mission-form-element-' + type + '-"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        if (elmt && elmt.readOnly === false && elmt.value.trim().length < 1) {
                            if (label.trim().length < 1) {
                                errors.push(messages.noLabel);
                            } else {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                            }
                        }
                    } else if (type === 'textarea') {
                        const elmt = container.querySelector('textarea[name^="cnrs-dm-front-mission-form-element-textarea-"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        if (elmt && elmt.readOnly === false && elmt.value.trim().length < 1) {
                            if (label.trim().length < 1) {
                                errors.push(messages.noLabel);
                            } else {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                            }
                        }
                    } else if (type === 'radio') {
                        const radioInputs = type === 'radio'
                            ? container.querySelectorAll('input[name^="cnrs-dm-front-mission-form-element-radio-"]')
                            : container.querySelectorAll('input[name="cnrs-dm-front-convention"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        let radioChecked = [];
                        for (let j = 0; j < radioInputs.length; j++) {
                            if (radioInputs[j].checked === true) {
                                radioChecked.push(j);
                            }
                        }
                        if (radioChecked.length < 1 && radioInputs.length > 0) {
                            if (label.trim().length < 1) {
                                errors.push(messages.noLabel);
                            } else {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.radio);
                            }
                        }
                        const optComments = container.querySelectorAll('.cnrs-dm-front-mission-form-opt-comment:required');
                        for (let j = 0; j < optComments.length; j++) {
                            if (optComments[j].value.trim().length < 1 && optComments[j].readOnly === false) {
                                const opt = optComments[j].previousElementSibling.querySelector('.text').dataset.label;
                                errors.push('<b>' + label + ' ' + opt + '</b>&nbsp;' + messages.option);
                            }
                        }
                    } else if (type === 'checkbox') {
                        const checkboxInputs = container.querySelectorAll('input[name^="cnrs-dm-front-mission-form-element-checkbox-"]');
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        let checkboxChecked = [];
                        for (let j = 0; j < checkboxInputs.length; j++) {
                            if (checkboxInputs[j].checked === true) {
                                checkboxChecked.push(j);
                            }
                        }
                        if (checkboxChecked.length < 1 && checkboxInputs.length > 0) {
                            if (label.trim().length < 1) {
                                errors.push(messages.noLabel);
                            } else {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.checkbox);
                            }
                        }
                        const optComments = container.querySelectorAll('.cnrs-dm-front-mission-form-opt-comment:required');
                        for (let j = 0; j < optComments.length; j++) {
                            if (optComments[j].value.trim().length < 1 && optComments[j].readOnly === false) {
                                const opt = optComments[j].previousElementSibling.querySelector('.checkbox__text-wrapper').dataset.label;
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
                    } else if (type === 'radio-convention') {
                        const label = container.querySelector('.cnrs-dm-front-mission-form-element-label').dataset.label;
                        if (hiddenConventionInput && hiddenConventionInput.readOnly === false) {
                            if (hiddenConventionInput.value.trim().length < 1) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.simple);
                            } else if (isNaN(hiddenConventionInput.value)) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.number);
                            } else if (parseInt(hiddenConventionInput.value) < 0) {
                                errors.push('<b>' + label + '</b>&nbsp;' + messages.unsigned);
                            }
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
                displayLoader();
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

    for (let i = 0; i < observationButtons.length; i++) {
        observationButtons[i].onclick = function () {
            const parent = this.closest('.cnrs-dm-front-revision-observation-wrapper');
            const index = parseInt(parent.dataset.index);
            if (observationButtons[i].classList.contains('add')) {
                this.classList.remove('add');
                this.classList.add('remove');
                this.innerHTML = this.dataset.remove;
                const textarea = `<div>
                    <input type="hidden" name="cnrs-dm-front-observation[${index}][index]" value="${index}">
                    <textarea class="cnrs-dm-front-observation-textarea" name="cnrs-dm-front-observation[${index}][observation]" required spellcheck="false" autocomplete="off"></textarea>
                </div>`;
                parent.insertAdjacentHTML('afterbegin', textarea);
            } else if (observationButtons[i].classList.contains('remove')) {
                this.classList.remove('remove');
                this.classList.add('add');
                this.innerHTML = this.dataset.add;
                parent.querySelector('textarea').remove();
            }
        }
    }

    if (revisionSubmitButton) {
        revisionSubmitButton.closest('form').onsubmit = function () {
            displayLoader();
        }
    }

    for (let i = 0; i < chooseDestMissionBtns.length; i++) {
        chooseDestMissionBtns[i].onclick = function () {
            const action = parseInt(this.dataset.choice);
            intlParagraph.innerHTML = action === 1 ? foreignMessage : franceMessage;
            desInput.value = action;
            isInternational = action === 1;
            chooseDestWrapper.remove();
            missionHTMLForm.classList.remove('cnrs-dm-front-mission-form-wrapper-init');
            displayToggledElementsLogic({
                id: missionLocationToggleUuid,
                option1: {
                    value: foreignLabel,
                    active: isInternational === false
                },
                option2: {
                    value: franceLabel,
                    active: isInternational === true
                },
            });
            resizeTooltips();
        }
    }

    if (typeof togglesObject !== "undefined") {
        checkAllInputsToggles();
        const containers = document.querySelectorAll('.cnrs-dm-front-mission-form-element[data-type="radio-toggle"]');
        for (let i = 0; i < containers.length; i++) {
            const inputs = containers[i].querySelectorAll('input[type="radio"]');
            for (let j = 0; j < inputs.length; j++) {
                inputs[j].oninput = function () {
                    toggleInputCheckedAction(this, inputs);
                }
            }
        }
    }

    if (hiddenConventionInput && textConventionInput) {
        textConventionInput.oninput = function () {
            const value = this.value;
            conventionError();
            if (value.length > 0) {
                for (let i = 0; i < conventionsLi.length; i++) {
                    if (conventionsLi[i].innerHTML.trim().toLowerCase().includes(value.toLowerCase())) {
                        conventionList.classList.add('display');
                        conventionsLi[i].style.display = 'list-item';
                    }
                }
            } else {
                conventionError();
            }
        };
        for (let i = 0; i < conventionsLi.length; i++) {
            conventionsLi[i].onclick = function () {
                for (let j = 0; j < conventionsLi.length; j++) {
                    conventionsLi[j].classList.remove('selected');
                }
                this.classList.add('selected');
                textConventionInput.value = conventionsLi[i].innerHTML.trim();
                hiddenConventionInput.value = conventionsLi[i].dataset.id;
                conventionList.classList.remove('display');
                textConventionInput.classList.remove('error');
            };
        }
    }
}

function conventionError() {
    hiddenConventionInput.removeAttribute('value');
    textConventionInput.classList.add('error');
    conventionList.classList.remove('display');
    for (let i = 0; i < conventionsLi.length; i++) {
        conventionsLi[i].style.display = 'none';
    }
}

function checkAllInputsToggles() {
    const containers = document.querySelectorAll('.cnrs-dm-front-mission-form-element[data-type="radio-toggle"]');
    for (let i = 0; i < containers.length; i++) {
        const inputs = containers[i].querySelectorAll('input[type="radio"]');
        for (let j = 0; j < inputs.length; j++) {
            if (inputs[j].checked === true) {
                toggleInputCheckedAction(inputs[j], inputs);
            }
        }
    }
}

function toggleInputCheckedAction(input, allInputs) {
    const uuid = input.dataset.uuid;
    const sibling = input.closest('.cnrs-dm-front-mission-form-element').nextElementSibling;
    if (sibling && sibling.querySelector('[name="cnrs-dm-front-funder-email"]')) {
        if (parseInt(input.value) === 1) {
            displayToggledElementsHTML([{html: sibling, toggles: {}, uuid: null}], [{html: null, toggles: {}, uuid: null}]);
        } else {
            displayToggledElementsHTML([{html: null, toggles: {}, uuid: null}], [{html: sibling, toggles: {}, uuid: null}]);
        }
    }
    let toggleLogic = {
        id: uuid,
        option1: {
            value: allInputs[0].parentElement.querySelector('span.text').innerHTML,
            active: allInputs[0].checked
        },
        option2: {
            value: allInputs[1].parentElement.querySelector('span.text').innerHTML,
            active: allInputs[1].checked
        },
    };
    if (allInputs[2]) {
        toggleLogic.option3 = {
            value: allInputs[2].parentElement.querySelector('span.text').innerHTML,
            active: allInputs[2].checked
        };
    }
    displayToggledElementsLogic(toggleLogic);
}

function displayToggledElementsLogic(triggerToggle) {

    let elementsToDisplay = [];
    let elementsToHide = [];
    if (missionForm) {
        for (let i = 0; i < missionForm.elements.length; i++) {
            const element = missionForm.elements[i];
            const toggles = element.data.toggles;
            if (toggles !== null) {
                for (const toggleUuid in toggles) {
                    const option1 = toggles[toggleUuid].option1;
                    const option2 = toggles[toggleUuid].option2;
                    const option3 = toggles[toggleUuid].option3;
                    if (triggerToggle.id === toggleUuid) {
                        const els = document.querySelectorAll('#cnrs-dm-front-dynamic-elements-wrapper .cnrs-dm-front-mission-form-element');
                        if (option1.active === false && triggerToggle.option1.active === true) {
                            elementsToHide.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                        }
                        if (option2.active === false && triggerToggle.option2.active === true) {
                            elementsToHide.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                        }
                        if (option1.active === true && triggerToggle.option1.active === true) {
                            elementsToDisplay.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                        }
                        if (option2.active === true && triggerToggle.option2.active === true) {
                            elementsToDisplay.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                        }
                        if (option3) {
                            if (option3.active === false && triggerToggle.option3.active === true) {
                                elementsToHide.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                            }
                            if (option3.active === true && triggerToggle.option3.active === true) {
                                elementsToDisplay.push({html: els[i], toggles: element.data.toggles, uuid: toggleUuid});
                            }
                        }
                    }
                }
            }
        }
    }
    displayToggledElementsHTML(elementsToDisplay, elementsToHide)
}

function displayToggledElementsHTML(elementsToDisplay, elementsToHide) {
    for (let i = 0; i < elementsToDisplay.length; i++) {
        const element = elementsToDisplay[i].html;
        if (element !== null) {
            if (hasNoDisabledToggle(elementsToDisplay[i].toggles, elementsToDisplay[i].uuid) === true) {
                element.classList.remove('hidden-by-toggle');
                const inputs = element.querySelectorAll('[name]');
                for (let j = 0; j < inputs.length; j++) {
                    inputs[j].disabled = false;
                }
            }
        }
    }
    for (let i = 0; i < elementsToHide.length; i++) {
        const element = elementsToHide[i].html;
        if (element !== null) {
            element.classList.add('hidden-by-toggle');
            const inputs = element.querySelectorAll('[name]');
            for (let j = 0; j < inputs.length; j++) {
                inputs[j].disabled = true;
            }
        }
    }
    resizeTooltips();
}

function hasNoDisabledToggle(toggles, activeToggleUuid) {
    if (activeToggleUuid === null) {
        return true
    }
    for (const uuid in toggles) {
        if (uuid !== activeToggleUuid) {
            const inputs = document.querySelectorAll(`.cnrs-dm-front-mission-form-element[data-uuid="${uuid}"] input[type="radio"]`);
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked === true) {
                    const value = inputs[i].value === '0';
                    if ((value === true && toggles[uuid].option1.active === false)
                        || (value === false && toggles[uuid].option2.active === false)
                    ) {
                        return false
                    }
                }
            }
        }
    }
    return true;
}

function displayLoader() {
    missionFormDiv.insertAdjacentHTML('beforeend', '<div id="cnrs-dm-front-loader-wrapper"><span id="cnrs-dm-front-loader"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40" height="40"><path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg></span></div>');
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
        const text = tooltipBtns[i].nextElementSibling;
        text.style.maxWidth = parentWidth + 'px';
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
