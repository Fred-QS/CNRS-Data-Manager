const cnrs_data_manager_provider = document.querySelector('#cnrs-data-manager-provider');
const dataManagerPageSegment = '?page=data-manager';
const generalURL = [
    window.location.protocol,
    '//',
    window.location.hostname,
    window.location.pathname,
    dataManagerPageSegment
].join('');
const limitSelector1 = document.querySelector('#cnrs-data-manager-limit-1');
const limitSelector2 = document.querySelector('#cnrs-data-manager-limit-2');
const limitInput = document.querySelector('input[name="cnrs-data-manager-limit"]');
const filenameInput = document.querySelector('input[name="cnrs-dm-filename"]');
const filenameError = document.querySelector('#cnrs-dm-filename-error-input');
const filenameSubmit = document.querySelector('.my-umr_page_settings #submit');
const shortCodes = document.querySelectorAll('.cnrs-data-manager-copy-shortcode');

prepareListeners();

function prepareListeners() {
    if (cnrs_data_manager_provider) {
        cnrs_data_manager_provider.addEventListener('change', function() {
            window.location.href = generalURL + '&cnrs-data-manager-provider=' + this.value;
        });
        limitSelector1.addEventListener('change', function() {
            limitSelector2.value = this.value;
            limitInput.value = this.value;
        });
        limitSelector2.addEventListener('change', function() {
            limitSelector1.value = this.value;
            limitInput.value = this.value;
        });
    }

    if (filenameInput) {
        filenameInput.addEventListener('input', function() {
            checkSettingsIntegrity();
        });
    }

    for (let i = 0; i < shortCodes.length; i++) {
        let element = shortCodes[i];
        let code = element.dataset.code;
        let svg = element.querySelector('svg');
        let modal = element.querySelector('.cnrs-dm-copied-to-clipboard');
        let timer;
        svg.onclick = function() {
            clearTimeout(timer);
            navigator.clipboard.writeText(code);
            modal.classList.add('display');
            timer = setTimeout(function() {
                modal.classList.remove('display');
            }, 2000);
        }
    }
}

function checkSettingsIntegrity() {
    if (filenameInput.value.length < 1 || filenameInput.value.length > 100) {
        filenameInput.classList.add('too-large');
        filenameError.classList.add('display-error');
        filenameSubmit.disabled = true;
    } else {
        filenameInput.classList.remove('too-large');
        filenameError.classList.remove('display-error');
        filenameSubmit.disabled = false;
    }
}