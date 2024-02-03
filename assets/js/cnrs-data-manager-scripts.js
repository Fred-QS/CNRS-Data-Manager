const cnrs_data_manager_provider = document.querySelector('#cnrs-data-manager-provider');
const dataManagerPageSegment = '?page=data-manager';
const generalURL = [
    window.location.protocol,
    '//',
    window.location.hostname,
    window.location.pathname,
    dataManagerPageSegment
].join('');
const regexLat = /^(-?[1-8]?\d(?:\.\d{1,18})?|90(?:\.0{1,18})?)$/;
const regexLon = /^(-?(?:1[0-7]|[1-9])?\d(?:\.\d{1,18})?|180(?:\.0{1,18})?)$/;
const limitSelector1 = document.querySelector('#cnrs-data-manager-limit-1');
const limitSelector2 = document.querySelector('#cnrs-data-manager-limit-2');
const limitInput = document.querySelector('input[name="cnrs-data-manager-limit"]');
const filenameInput = document.querySelector('input[name="cnrs-dm-filename"]');
const filenameError = document.querySelector('#cnrs-dm-filename-error-input');
const filenameSubmit = document.querySelector('.my-umr_page_settings #submit');
const shortCodes = document.querySelectorAll('.cnrs-data-manager-copy-shortcode');
const addMarkersButton = document.querySelector('#cnrs-dm-marker-adder');
const markersContainer = document.querySelector('#cnrs-dm-markers-list');
const markersReferencesLabels = document.querySelector('#cnrs-dm-map-reference-labels');
const noMarkerResult = document.querySelector('#cnrs-dm-no-marker');

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

    if (addMarkersButton) {
        addKeyupOnCoordsInput();
        checkCoordsIntegrity('main');
        checkCoordsIntegrity('markers');
        addMarkersButton.addEventListener('click', function() {
            addNewMarkerInputs();
            deleteMarker();
            addKeyupOnCoordsInput();
        });
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

function addNewMarkerInputs() {
    let labels = {
        'lat': markersReferencesLabels.dataset.lat,
        'lng': markersReferencesLabels.dataset.lng,
        'title': markersReferencesLabels.dataset.title,
        'delete': markersReferencesLabels.dataset.delete
    };
    const markers = document.querySelectorAll('.cnrs-dm-marker-container');
    noMarkerResult.classList.add('hide');
    let index = markers.length;
    let newMarker = document.createElement('tr');
    newMarker.className = 'cnrs-dm-marker-container';
    newMarker.dataset.index = index;
    newMarker.innerHTML = `<td><table class="form-table" role="presentation"><tbody>
    <tr>
        <th scope="row"><label for="cnrs-dm-marker-title-${index}">${labels.title}</label></th>
        <td><p><input required name="cnrs-dm-marker-title-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-title-${index}" class="regular-text"></p></td>
    </tr>
    <tr>
        <th scope="row"><label for="cnrs-dm-marker-lat-${index}">${labels.lat}</label></th>
        <td><p><input required name="cnrs-dm-marker-lat-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lat-${index}" class="regular-text"></p></td>
    </tr>
    <tr>
        <th scope="row"><label for="cnrs-dm-marker-lng-${index}">${labels.lng}</label></th><td><p><input required name="cnrs-dm-marker-lng-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lng-${index}" class="regular-text"></p></td>
    </tr>
    <tr>
        <td colspan="2" class="cnrs-dm-td-no-padding"><input type="button" id="cnrs-dm-marker-delete-${index}" class="button button-danger" value="${labels.delete}"></td>
    </tr></tbody></table></td>`;
    markersContainer.appendChild(newMarker);
    deleteMarker();
    addKeyupOnCoordsInput();
    checkCoordsIntegrity('markers');
}

function deleteMarker() {
    let deleteButtons = document.querySelectorAll('[id^="cnrs-dm-marker-delete-"]');
    for (let i = 0; i < deleteButtons.length; i++) {
        let button = deleteButtons[i];
        button.addEventListener('click', function() {
            let container = button.closest('.cnrs-dm-marker-container');
            container.remove();
            if (markersContainer.children.length === 0) {
                noMarkerResult.classList.remove('hide');
            } else {
                refactorMarkerOrder();
            }
            checkCoordsIntegrity();
        });
    }
}

function refactorMarkerOrder() {
    let markerContainers = document.querySelectorAll('.cnrs-dm-marker-container');
    for (let i = 0; i < markerContainers.length; i++) {
        let container = markerContainers[i];
        container.querySelector('label[for^="cnrs-dm-marker-title-"]').setAttribute('for', `cnrs-dm-marker-title-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-title-"]').setAttribute('id', `cnrs-dm-marker-title-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-title-"]').setAttribute('name', `cnrs-dm-marker-title-${i}`);
        container.querySelector('label[for^="cnrs-dm-marker-lat-"]').setAttribute('for', `cnrs-dm-marker-lat-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-lat-"]').setAttribute('id', `cnrs-dm-marker-lat-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-lat-"]').setAttribute('name', `cnrs-dm-marker-lat-${i}`);
        container.querySelector('label[for^="cnrs-dm-marker-lng-"]').setAttribute('for', `cnrs-dm-marker-lng-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-lng-"]').setAttribute('id', `cnrs-dm-marker-lng-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-lng-"]').setAttribute('name', `cnrs-dm-marker-lng-${i}`);
        container.querySelector('input[id^="cnrs-dm-marker-delete-"]').setAttribute('id', `cnrs-dm-marker-delete-${i}`);
    }
}

function checkCoordsIntegrity(type) {
    if (type === 'main') {
        let mainLat = document.querySelector('#cnrs-dm-main-lat');
        let mainLng = document.querySelector('#cnrs-dm-main-lng');
        let submit = document.querySelector('#submit-settings');
        let errorLat = true;
        let errorLng = true;
        if (mainLat.value.length > 3 && regexLat.test(mainLat.value)) {
            mainLat.classList.remove('cnrs-dm-error');
            errorLat = false;
        } else {
            if (mainLat.value.length > 0) {
                mainLat.classList.add('cnrs-dm-error');
            } else {
                mainLat.classList.remove('cnrs-dm-error');
            }
            errorLat = true;
        }
        if (mainLng.value.length > 3 && regexLon.test(mainLng.value)) {
            mainLng.classList.remove('cnrs-dm-error');
            errorLng = false;
        } else {
            if (mainLng.value.length > 0) {
                mainLng.classList.add('cnrs-dm-error');
            } else {
                mainLng.classList.remove('cnrs-dm-error');
            }
            errorLng = true;
        }
        submit.disabled = errorLat || errorLng;
    } else if (type === 'markers') {
        let markersTitle = document.querySelectorAll('input[id^="cnrs-dm-marker-title-"]');
        let markersLat = document.querySelectorAll('input[id^="cnrs-dm-marker-lat-"]');
        let markersLng = document.querySelectorAll('input[id^="cnrs-dm-marker-lng-"]');
        let submit = document.querySelector('#submit-markers');
        let error = 0;
        for (let i = 0; i < markersTitle.length; i++) {
            let markerTitle = markersTitle[i];
            if (markerTitle.value.length > 3) {
                markerTitle.classList.remove('cnrs-dm-error');
            } else {
                if (markerTitle.value.length > 0) {
                    markerTitle.classList.add('cnrs-dm-error');
                } else {
                    markerTitle.classList.remove('cnrs-dm-error');
                }
                error++;
            }
        }
        for (let i = 0; i < markersLat.length; i++) {
            let markerLat = markersLat[i];
            if (markerLat.value.length > 3 && regexLat.test(markerLat.value)) {
                markerLat.classList.remove('cnrs-dm-error');
            } else {
                if (markerLat.value.length > 0) {
                    markerLat.classList.add('cnrs-dm-error');
                } else {
                    markerLat.classList.remove('cnrs-dm-error');
                }
                error++;
            }
        }
        for (let i = 0; i < markersLng.length; i++) {
            let markerLng = markersLng[i];
            if (markerLng.value.length > 3 && regexLon.test(markerLng.value)) {
                markerLng.classList.remove('cnrs-dm-error');
            } else {
                if (markerLng.value.length > 0) {
                    markerLng.classList.add('cnrs-dm-error');
                } else {
                    markerLng.classList.remove('cnrs-dm-error');
                }
                error++;
            }
        }
        submit.disabled = markersTitle.length === 0 || error > 0;
    }
}

function addKeyupOnCoordsInput() {

    let mainLat = document.querySelector('#cnrs-dm-main-lat');
    let mainLng = document.querySelector('#cnrs-dm-main-lng');
    mainLat.onkeyup = function(e) {
        checkCoordsIntegrity('main');
    }
    mainLng.onkeyup = function(e) {
        checkCoordsIntegrity('main');
    }
    let markersTitle = document.querySelectorAll('input[id^="cnrs-dm-marker-title-"]');
    let markersLat = document.querySelectorAll('input[id^="cnrs-dm-marker-lat-"]');
    let markersLng = document.querySelectorAll('input[id^="cnrs-dm-marker-lng-"]');
    for (let i = 0; i < markersTitle.length; i++) {
        markersTitle[i].onkeyup = function(e) {
            checkCoordsIntegrity('markers');
        }
    }
    for (let i = 0; i < markersLat.length; i++) {
        markersLat[i].onkeyup = function(e) {
            checkCoordsIntegrity('markers');
        }
    }
    for (let i = 0; i < markersLng.length; i++) {
        markersLng[i].onkeyup = function(e) {
            checkCoordsIntegrity('markers');
        }
    }
}