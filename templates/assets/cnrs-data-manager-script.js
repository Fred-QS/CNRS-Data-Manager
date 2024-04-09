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
const optionalInputs = document.querySelectorAll('.cnrs-dm-front-checkbox-label[data-option="option"]')

window.addEventListener('load', function(){
    isMobile();
    displayContainers();
    dispatchWrapperListener();
    prepareMissionForm();
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
    if (missionForm !== undefined) {
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
                    penColor: "#68c0b5"
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