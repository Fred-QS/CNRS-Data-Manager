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
const filenameSubmit = document.querySelector('body[class*="data-manager-settings"] #submit');
const shortCodes = document.querySelectorAll('.cnrs-data-manager-copy-shortcode');
const addMarkersButton = document.querySelector('#cnrs-dm-marker-adder');
const markersContainer = document.querySelector('#cnrs-dm-markers-list');
const markersReferencesLabels = document.querySelector('#cnrs-dm-map-reference-labels');
const noMarkerResult = document.querySelector('#cnrs-dm-no-marker');
const mapPreview = document.querySelector('#cnrs-dm-map-preview');
const mapPreviewButton = document.querySelector('#cnrs-dm-open-map-preview');
const mapPreviewCloseButton = document.querySelector('#cnrs-dm-close-map-preview');
const mapPreviewRefreshButton = document.querySelector('#cnrs-dm-refresh-map-preview');
const mapCanvasContainer = document.querySelector('.cnrs-dm-map');
const mapViewSelector = document.querySelector('#cnrs-dm-map-settings-view');
const blackBgRadios = document.querySelectorAll('[name="cnrs-dm-map-settings-black_bg"]');
const shortCodeFilterContainer = document.querySelectorAll('.cnrs-dm-shortcode-filters');
const mapControls = document.querySelector('#cnrs-dm-map-controls');
const getCoordsBtn = document.querySelector('#cnrs-dm-localize-me-container');
const restoreFoldersMessage = document.querySelector('#cnrs-dm-restore-message');
const modeDisplaySelector = document.querySelector('#cnrs-dm-mode');
const modeDisplayInfo = document.querySelector('#cnrs-dm-page-option-shortcode');
const filnameStates = document.querySelectorAll('.cnrs-dm-filename-states');
const filenameStateOk = document.querySelector('#cnrs-dm-filename-good');
const filenameStateKo = document.querySelector('#cnrs-dm-filename-bad');
const filenameStateRefresh = document.querySelector('#cnrs-dm-filename-refresh');
const fileImportForm = document.querySelector('#cnrs-dm-file-import-form');
const fileImportInput = document.querySelector('#cnrs-dm-import-file');
const fileImportBtn = document.querySelector('#cnrs-dm-import-file-btn');
const fileImportSubmitBtn = document.querySelector('#cnrs-dm-file-import-form-submit');
const importInitialStateContainer = document.querySelector('#cnrs-dm-import-initial-state-container');
const importResponseStateContainer = document.querySelector('#cnrs-dm-import-response-state-container');
let filenameTimeout;
let xlsFile = null;

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

    if (modeDisplaySelector) {
        modeDisplaySelector.addEventListener('change', function (){
            if (this.value === 'page') {
                modeDisplayInfo.classList.remove('hide');
            } else {
                modeDisplayInfo.classList.add('hide');
            }
        });
    }

    if (filenameInput) {
        checkURLValidity(filenameInput.value);
        filenameInput.addEventListener('input', function() {
            checkSettingsIntegrity();
            checkURLValidity(this.value);
        });
    }

    if (restoreFoldersMessage) {
        setTimeout(function(){
            restoreFoldersMessage.classList.add('hide');
        }, 3000);
        setTimeout(function(){
            restoreFoldersMessage.remove();
        }, 3250);
    }

    addDisplayRowMarkerListener();

    for (let i = 0; i < shortCodes.length; i++) {
        let element = shortCodes[i];
        let svg = element.querySelector('svg');
        let modal = element.querySelector('.cnrs-dm-copied-to-clipboard');
        let codeContent = element.querySelector('code');
        let timer;
        svg.onclick = function() {
            clearTimeout(timer);
            let code = codeContent.innerHTML;
            navigator.clipboard.writeText(code);
            modal.classList.add('display');
            timer = setTimeout(function() {
                modal.classList.remove('display');
            }, 2000);
        }
    }

    if (shortCodeFilterContainer.length > 0) {
        initSettingsShortCodeFilters();
    }

    if (mapPreview) {
        dragElement(mapPreview);
        checkIntegrityFromMapView(mapViewSelector.value);
        if (getCoordsBtn) {
            if (navigator.geolocation) {
                getCoordsBtn.addEventListener('click', function (){
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        document.querySelector('#cnrs-dm-main-lat').value = latitude;
                        document.querySelector('#cnrs-dm-main-lng').value = longitude;
                    });
                });
            } else {
                getCoordsBtn.querySelector('#cnrs-dm-localize-me').add('hide');
                getCoordsBtn.querySelector('#cnrs-dm-not-supported').remove('hide');
            }
        }
        mapViewSelector.addEventListener('change', function (){
            checkIntegrityFromMapView(this.value);
        });
        mapPreviewButton.addEventListener('click', function (){
            refreshMapPreview();
            mapPreview.classList.add('show');
            this.disabled = true;
        });
        mapPreviewCloseButton.addEventListener('click', function (){
            mapPreview.classList.remove('show');
            mapPreviewButton.disabled = false;
        });
        mapPreviewRefreshButton.addEventListener('click', function (){
            refreshMapPreview();
        });

        for (let i = 0; i < blackBgRadios.length; i++) {
            blackBgRadios[i].addEventListener('input', function (){
                let value =  this.value;
                if (value === '1') {
                    if (mapViewSelector.value === 'space') {
                        // Disable input for submit
                        document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').disabled = false;
                        document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="1"]').disabled = false;
                        document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').disabled = false;
                        document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="1"]').disabled = false;
                    }
                } else {
                    // Select for UI
                    document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').checked = true;
                    // Disable input for submit
                    document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').disabled = true;
                    document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="1"]').disabled = true;
                    // Select for UI
                    document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').checked = true;
                    // Disable input for submit
                    document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').disabled = true;
                    document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="1"]').disabled = true;
                }
            });
        }
    }

    if (addMarkersButton) {
        addKeyupOnCoordsInput();
        deleteMarker();
        checkCoordsIntegrity('main');
        checkCoordsIntegrity('markers');
        addMarkersButton.addEventListener('click', function() {
            addNewMarkerInputs();
            deleteMarker();
            addKeyupOnCoordsInput();
        });
    }

    if (fileImportForm) {

        fileImportForm.addEventListener('submit', function (e){
            e.preventDefault();
            if (fileImportInput.files[0]) {
                xlsFile = fileImportInput.files[0];
                const formData = new FormData();
                const url = '/wp-admin/admin-ajax.php';
                formData.append('file', fileImportInput.files[0]);
                formData.append('action', 'check_xml_file');
                fileImportInput.value = null;
                importState(1);
                const options = {
                    method: 'POST',
                    body: formData,
                };
                fetch(url, options)
                    .then(
                        response => response.json()
                    ).then(
                        success => handleXMLCheckResult(success.data)
                    ).catch(
                        error => handleXMLCheckResult({error: fileImportInput.dataset.error, data: null, html: null})
                    );
            }
        });

        fileImportBtn.addEventListener('click', function(){
            fileImportInput.click();
        });

        fileImportInput.addEventListener('input', function(e){
            if (e.target.files.length === 1 && e.target.files[0].type === 'application/zip') {
                xlsFile = null;
                fileImportSubmitBtn.click();
            }
        });
    }
}

function handleXMLCheckResult(json) {
    importState(2);
    if (json.error === null) {
        let xlsArray = json.data;
        importResponseStateContainer.innerHTML = json.html;
        let form = document.querySelector('#cnrs-dm-import-confirm-form');
        form.onsubmit = function(e) {
            e.preventDefault();
            console.log(xlsFile, xlsArray);
        };
    } else {
        importResponseStateContainer.innerHTML = `<ul>
            <li class="cnrs-dm-import-state-pending">
                <i class="cnrs-dm-import-state-response cnrs-dm-import-state">1.&nbsp;${fileImportInput.dataset.step1}</i>
                <span class="cnrs-dm-import-state-logo">
                    <svg id="cnrs-dm-import-good" viewBox="0 0 448 512">
                        <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                    </svg>
                </span>
            </li>
            <li class="cnrs-dm-import-state-pending">
                <i class="cnrs-dm-import-state-response cnrs-dm-import-state">2.&nbsp;${fileImportInput.dataset.step2}</i>
                <span class="cnrs-dm-import-state-logo">
                    <svg id="cnrs-dm-import-bad" viewBox="0 0 384 512">
                        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                    </svg>
                </span>
            </li>
        </ul>
        <p class="cnrs-dm-import-error-message">${json.error}</p>`;
    }
}

function importState(step) {
    if (step === 1) {
        importResponseStateContainer.innerHTML = '';
        importInitialStateContainer.classList.remove('hide');
        let svg = importInitialStateContainer.querySelectorAll('.cnrs-dm-import-state-logo');
        for (let i = 0; i < svg.length; i++) {
            svg[i].classList.remove('hide');
        }
        importInitialStateContainer.querySelector('.cnrs-dm-import-state-response').classList.remove('hide');
        importInitialStateContainer.querySelector('.cnrs-dm-import-state-waiting').classList.add('hide');
    } else if (step === 2) {
        importInitialStateContainer.classList.add('hide');
    }
}

function checkURLValidity(value) {
    for (let i = 0; i < filnameStates.length; i++) {
        filnameStates[i].classList.remove('cnrs-dm-display-state');
    }
    filenameStateRefresh.classList.add('cnrs-dm-display-state');
    clearTimeout(filenameTimeout);
    filenameTimeout = setTimeout(async function(){
        fetch(value)
            .then(response => response.text())
            .then(str => (new window.DOMParser()).parseFromString(str, "text/xml"))
            .then(data => getDataFromXML(data))
            .catch((error) => {
                getDataFromXML();
            });
    }, 1000);
}

function getDataFromXML(data = null) {
    let error = true;
    if (data !== null
        && data.querySelector('reference')
        && data.querySelector('reference > equipes')
        && data.querySelector('reference > services')
        && data.querySelector('reference > plateformes')
        && data.querySelector('reference > agents')
        && data.querySelectorAll('reference > equipes > equipe').length > 0
        && data.querySelectorAll('reference > services > service').length > 0
        && data.querySelectorAll('reference > plateformes > plateforme').length > 0
        && data.querySelectorAll('reference > agents > agent').length > 0
    ) {
        error = false;
    }
    for (let i = 0; i < filnameStates.length; i++) {
        filnameStates[i].classList.remove('cnrs-dm-display-state');
    }
    if (error === true) {
        filenameStateKo.classList.add('cnrs-dm-display-state');
        filenameSubmit.disabled = true;
    } else {
        filenameStateOk.classList.add('cnrs-dm-display-state');
        filenameSubmit.disabled = false;
    }
}

function addDisplayRowMarkerListener() {
    const markersOpenButtons = document.querySelectorAll('.cnrs-dm-markers-toggle');
    for (let i = 0; i < markersOpenButtons.length; i++) {
        markersOpenButtons[i].onclick = function (){
            const rows = this.closest('table').querySelectorAll('.cnrs-dm-marker-row');
            if (this.classList.contains('show')) {
                this.classList.remove('show');
                for (let j = 0; j < rows.length; j++) {
                    rows[j].classList.add('cnrs-dm-marker-row-hide');
                }
            } else {
                this.classList.add('show');
                for (let j = 0; j < rows.length; j++) {
                    rows[j].classList.remove('cnrs-dm-marker-row-hide');
                }
            }
        };
    }
}

function checkIntegrityFromMapView(view) {
    // Sunlight
    document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').disabled = false;
    document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="1"]').disabled = false;
    // Stars
    document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').disabled = false;
    document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="1"]').disabled = false;
    // Black background
    document.querySelector('input[name="cnrs-dm-map-settings-black_bg"][value="0"]').disabled = false;
    document.querySelector('input[name="cnrs-dm-map-settings-black_bg"][value="1"]').disabled = false;
    // Atmosphere
    document.querySelector('input[name="cnrs-dm-map-settings-atmosphere"][value="0"]').disabled = false;
    document.querySelector('input[name="cnrs-dm-map-settings-atmosphere"][value="1"]').disabled = false;
    if (view !== 'space') {
        // Select for UI
        document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').checked = true;
        // Disable input for submit
        document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="0"]').disabled = true;
        document.querySelector('input[name="cnrs-dm-map-settings-sunlight"][value="1"]').disabled = true;
        // Select for UI
        document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').checked = true;
        // Disable input for submit
        document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="0"]').disabled = true;
        document.querySelector('input[name="cnrs-dm-map-settings-stars"][value="1"]').disabled = true;
    }
    if (['news', 'cork'].includes(view)) {
        // Select for UI
        document.querySelector('input[name="cnrs-dm-map-settings-atmosphere"][value="0"]').checked = true;
        // Disable input for submit
        document.querySelector('input[name="cnrs-dm-map-settings-atmosphere"][value="0"]').disabled = true;
        document.querySelector('input[name="cnrs-dm-map-settings-atmosphere"][value="1"]').disabled = true;
    }
}

function checkSettingsIntegrity() {
    if (filenameInput.value.length > 255) {
        filenameInput.classList.add('too-large');
        filenameError.classList.add('display-error');
    } else if (filenameInput.value.length < 1) {
        filenameInput.classList.remove('too-large');
        filenameError.classList.remove('display-error');
    } else if (filenameInput.value.length < 8) {
        filenameInput.classList.add('too-large');
        filenameError.classList.remove('display-error');
    } else {
        filenameInput.classList.remove('too-large');
        filenameError.classList.remove('display-error');
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
    newMarker.innerHTML = `<td><div class="cnrs-dm-map-marker-container"><table class="form-table" role="presentation"><tbody>
    <tr class="cnrs-dm-marker-first-row">
        <th scope="row"><label for="cnrs-dm-marker-title-${index}">${labels.title}</label><span class="cnrs-dm-markers-toggle show"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path fill="#2271b1" d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM135 241c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l87 87 87-87c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9L273 345c-9.4 9.4-24.6 9.4-33.9 0L135 241z"/></svg></span></th>
        <td><p><input required name="cnrs-dm-marker-title-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-title-${index}" class="regular-text"></p></td>
    </tr>
    <tr class="cnrs-dm-marker-row">
        <th scope="row"><label for="cnrs-dm-marker-lat-${index}">${labels.lat}</label></th>
        <td><p><input required name="cnrs-dm-marker-lat-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lat-${index}" class="regular-text"></p></td>
    </tr>
    <tr class="cnrs-dm-marker-row">
        <th scope="row"><label for="cnrs-dm-marker-lng-${index}">${labels.lng}</label></th><td><p><input required name="cnrs-dm-marker-lng-${index}" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lng-${index}" class="regular-text"></p></td>
    </tr>
    <tr class="cnrs-dm-marker-row">
        <td colspan="2" class="cnrs-dm-td-no-padding"><input type="button" id="cnrs-dm-marker-delete-${index}" class="button button-danger" value="${labels.delete}"></td>
    </tr></tbody></table></div></td>`;
    markersContainer.appendChild(newMarker);
    deleteMarker();
    addKeyupOnCoordsInput();
    addDisplayRowMarkerListener();
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
            checkCoordsIntegrity('markers');
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

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.querySelector('#' + elmnt.id + "-header")) {
        // if present, the header is where you move the DIV from:
        document.querySelector('#' + elmnt.id + "-header").onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        if (window.innerWidth > 782) {
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }
    }

    function closeDragElement() {
        if (window.innerWidth > 782) {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
}

function refreshMapPreview() {

    let json = {
        main: {
            lat: document.querySelector('[name="cnrs-dm-main-lat"]').value,
            lng: document.querySelector('[name="cnrs-dm-main-lng"]').value
        },
        sunlight: document.querySelector('[name="cnrs-dm-map-settings-sunlight"]:checked').value === '1',
        view: document.querySelector('[name="cnrs-dm-map-settings-view"]').value,
        stars: document.querySelector('[name="cnrs-dm-map-settings-stars"]:checked').value === '1',
        black_bg: document.querySelector('[name="cnrs-dm-map-settings-black_bg"]:checked').value === '1',
        atmosphere: document.querySelector('[name="cnrs-dm-map-settings-atmosphere"]:checked').value === '1',
        markers: (function(){
            let titles = document.querySelectorAll('[name^="cnrs-dm-marker-title-"]');
            let coords = [];
            for (let i = 0; i < titles.length; i++) {
                let title = document.querySelector(`[name="cnrs-dm-marker-title-${i}"]`);
                let lat = document.querySelector(`[name="cnrs-dm-marker-lat-${i}"]`);
                let lng = document.querySelector(`[name="cnrs-dm-marker-lng-${i}"]`);
                if (title.value.length > 0
                    && lat.value.length > 3
                    && regexLat.test(lat.value)
                    && lng.value.length > 3
                    && regexLon.test(lng.value))
                {
                    coords.push({'title': title.value, 'lat': lat.value, 'lng': lng.value});
                }
            }
            return coords;
        })()
    }

    let html = `<pre style="display: none;" class="cnrs-dm-map-data">${JSON.stringify(json)}</pre>`;
    html += json.atmosphere === true ? '<div id="cnrs-dm-map-atmosphere"></div>' : '';
    mapCanvasContainer.innerHTML = html;
    if (json.sunlight === true) {
        mapControls.classList.remove('hide');
    } else {
        mapControls.classList.add('hide');
    }
    prepareWorldMap();
}

function initSettingsShortCodeFilters() {
    for (let i = 0; i < shortCodeFilterContainer.length; i++) {
        let container = shortCodeFilterContainer[i];
        let inputs = container.querySelectorAll('input[type="radio"]:not([name^="cnrs-dm-selector"])');
        for (let j = 0; j < inputs.length; j++) {
            inputs[j].addEventListener('input', function() {
                if (this.checked === true) {
                    let parent = this.closest('td');
                    let ref = parent.querySelector('.cnrs-data-manager-copy-shortcode').dataset.code.slice(0, -1);
                    let splitName = this.name.split('-');
                    let key = splitName[splitName.length - 1];
                    let filter = parent.querySelector('input[name="cnrs-dm-filter-' + key + '"]:checked').value;
                    filter = filter.length > 0 ? ' ' + filter : '';
                    let view = parent.querySelector('input[name="cnrs-dm-view-' + key + '"]:checked').value;
                    view = view.length > 0 ? ' ' + view : '';
                    parent.querySelector('code').innerHTML = ref + filter + view + ']';
                }
            });
        }
    }
}