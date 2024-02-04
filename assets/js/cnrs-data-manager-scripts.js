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
const mapPreview = document.querySelector('#cnrs-dm-map-preview');
const mapPreviewButton = document.querySelector('#cnrs-dm-open-map-preview');
const mapPreviewCloseButton = document.querySelector('#cnrs-dm-close-map-preview');
const mapPreviewRefreshButton = document.querySelector('#cnrs-dm-refresh-map-preview');
const markersOpenButtons = document.querySelectorAll('.cnrs-dm-markers-toggle');
const mapCanvasContainer = document.querySelector('.cnrs-dm-map');


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

    if (mapPreview) {
        dragElement(mapPreview);
        mapPreviewButton.addEventListener('click', function (){
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
        for (let i = 0; i < markersOpenButtons.length; i++) {
            markersOpenButtons[i].addEventListener('click', function (){
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
            return [];
        })()
    }

    let html = `<pre style="display: none;" class="cnrs-dm-map-data">${JSON.stringify(json)}</pre>`;
    html += json.atmosphere === true ? '<div id="cnrs-dm-map-atmosphere"></div>' : '';
    html += json.sunlight === true ? '<div id="cnrs-dm-map-controls"><div id="cnrs-dm-map-sun-slider-wrap"><input type="range" min="0" max="360" value="90" id="cnrs-dm-map-sun-slider"></div></div>' : '';
    html += json.view === 'space' ? '<div id="cnrs-dm-map-res" style="display: none;"><img alt="day-view" id="cnrs-dm-map-day" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/space-view/day-by-nasa.jpg"><img alt="night-view" id="cnrs-dm-map-night" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/space-view/night-by-nasa.jpg"></div>' : '';
    html += json.view === 'cork' ? '<div id="cnrs-dm-map-res" style="display: none;"><img alt="cork-texture" id="cnrs-dm-map-cork" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/cork/cork.jpg"></div>' : '';
    mapCanvasContainer.innerHTML = html;
    prepareWorldMap();
}