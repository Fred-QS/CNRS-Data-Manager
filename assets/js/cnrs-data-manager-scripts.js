/*********************************************************************************************
 *                              CNRS Data Manager JS                              *
 ********************************************************************************************/

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
const regexLng = /^(-?(?:1[0-7]|[1-9])?\d(?:\.\d{1,18})?|180(?:\.0{1,18})?)$/;
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
const fileImportTeamSelector = document.querySelector('#cnrs-data-manager-projects-team');
const projectInputSearch = document.querySelector('#cnrs-data-manager-search-projects');
const projectSearchButton = document.querySelector('#cnrs-data-manager-search-submit-projects');
const projectExpander = document.querySelectorAll('.cnrs-dm-projects-expander');
const missionFormTabs = document.querySelectorAll('.cnrs-dm-tabs-container');
const missionFormTabContents = document.querySelectorAll('.cnrs-dm-tab-content');
const missionFormTabSeparators = document.querySelectorAll('.cnrs-dm-tab-separator');
const missionFormTools = document.querySelectorAll('.cnrs-dm-add-tool');
const missionFormStructure = document.querySelector('#cnrs-dm-form-structure');
const missionFormTitle = document.querySelector('textarea[name="cnrs-dm-form-title"]');
const adminWrapper = document.querySelector('.cnrs-data-manager-page');
const missionFormPreview = document.querySelector('#cnrs-dm-form-preview-container');
const wpContainer = document.querySelector('#wpcontent');
const missionFormSubmit = document.querySelector('#cnrs-dm-mission-form-submit');
const missionFormFinal = document.querySelector('#cnrs-dm-mission-form-final');
const missionFormFinalInput = document.querySelector('input[name="cnrs-dm-mission-form"]');
const missionFormPage = document.querySelector('#cnrs-data-manager-mission-form-page');
const missionFormListContainer = document.querySelector('#cnrs-dm-mission-form-list-container');
const missionListLoader = document.querySelector('#cnrs-dm-mission-form-loader-container');
const missionFormSearch = document.querySelector('#cnrs-data-manager-mission-search');
const missionFormTotal = document.querySelector('#cnrs-dm-mission-form-total span');
const adminFormLinkCopy = document.querySelector('#cnrs-dm-form-link-to-form span');
const deleteManagerButton = document.querySelectorAll('.cnrs-dm-manager-delete-button');
const addManagerButton = document.querySelector('.cnrs-dm-tool-button[data-action="add-manager"]');
const managerList = document.querySelector('#cnrs-dm-managers-list');
const addAdminEmailButton = document.querySelector('#cnrs-dm-admin-emails-button');
const adminEmailsWrapper = document.querySelector('#cnrs-dm-admin-emails-wrapper');

const tinyMCEConfig = {
    width: "100%",
    height: 250,
    resize: false,
    plugins: ['anchor', 'lists'],
    toolbar: 'undo redo | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
    content_style: 'body { font-family:inherit,sans-serif; font-size:14px }',
    statusbar: false,
    forced_root_block: 'p',
    newline_behavior: '',
    newline_behavior: 'block'
}
let filenameTimeout;
let xlsFile = null;
let wpContainerWidth = 0;
let agentsList = [];

prepareListeners();
setToolsListeners(true);
deleteManagerButtonsAction();

function prepareListeners() {
    if (wpContainer) {
        new ResizeSensor(wpContainer, function(){
            wpContainerWidth = wpContainer.scrollWidth;
            let modalWrapper = document.querySelector('#cnrs-dm-form-modal-wrapper');
            if (modalWrapper) {
                modalWrapper.style.width = wpContainerWidth + 'px';
                modalWrapper.style.left = (window.innerWidth - wpContainerWidth) + 'px';
            }
        });
    }

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

    if (projectSearchButton) {
        projectSearchButton.onclick = function () {
            const input = projectInputSearch.value;
            const rows = document.querySelectorAll('.cnrs-dm-projects-row');
            for (let i = 0; i < rows.length; i++) {
                rows[i].classList.remove('hide');
                rows[i].classList.remove('even');
            }
            if (input.length > 0) {
                for (let i = 0; i < rows.length; i++) {
                    if (!rows[i].querySelector('.cnrs-dm-project-item a span').innerHTML.toLowerCase().includes(input.toLowerCase())) {
                        rows[i].classList.add('hide');
                    }
                }
            }
            const displayed = document.querySelectorAll('.cnrs-dm-projects-row:not(.hide)')
            for (let i = 0; i < displayed.length; i++) {
                if ((i + 1) % 2 === 0) {
                    displayed[i].classList.add('even');
                }
            }
        }
    }

    for (let i = 0; i < projectExpander.length; i++) {
        projectExpander[i].onclick = function() {
            const tr = this.closest('tr');
            if (tr.classList.contains('expand')) {
                tr.classList.remove('expand');
            } else {
                tr.classList.add('expand');
            }
        }
    }

    for (let i = 0; i < missionFormTabs.length; i++) {
        missionFormTabs[i].onclick = function () {
            let index = 0;
            for (let j = 0; j < missionFormTabs.length; j++) {
                missionFormTabs[j].classList.remove('active');
                missionFormTabContents[j].classList.remove('active');
                missionFormTabSeparators[j].classList.remove('active');
                index = this.dataset.tab === missionFormTabContents[j].dataset.content ? j : index;
            }

            let query = window.location.search;
            let params = query.length > 0
                ? query.replace('?', '').split('&')
                : [];
            for (let j = 0; j < params.length; j++) {
                if (params[j].includes('tab=')) {
                    params.splice(j, 1);
                }
            }
            let cleanQuery = params.length > 0 ? '?' + params.join('&') : '';
            cleanQuery += ['list', 'settings'].includes(this.dataset.tab) ? '&tab=' + this.dataset.tab : '';
            window.history.replaceState({}, '', window.location.pathname + cleanQuery);

            missionFormTabs[index].classList.add('active');
            missionFormTabContents[index].classList.add('active');
            missionFormTabSeparators[index].classList.add('active');
        }
    }

    for (let i = 0; i < missionFormTools.length; i++) {
        missionFormTools[i].onclick = function() {
            const error = this.closest('#cnrs-dm-form-tools').dataset.error;
            const formData = new FormData();
            const url = '/wp-admin/admin-ajax.php';
            formData.append('action', 'set_form_tool');
            formData.append('tool', this.dataset.tool);
            formData.append('iteration', document.querySelectorAll('.cnrs-dm-form-tool-render').length);
            const options = {
                method: 'POST',
                body: formData,
            };
            fetch(url, options)
                .then(
                    response => response.json()
                ).then(
                success => addFormTool(success.data)
            ).catch(
                error => addFormTool({error: error, data: null, html: null, json: '[]'})
            );
        }
    }

    if (missionFormTitle) {
        resizeTextAreaOnLoad();
        missionFormTitle.oninput = function() {
            missionForm.title = this.value;
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
            if (this.value.substr(0, this.selectionStart).split("\n").length > 1) {
                this.style.marginBottom = 34 + 'px';
            } else {
                this.style.marginBottom = 0 + 'px';
            }
        }
    }

    if (missionFormSubmit) {
        missionFormSubmit.onclick = function() {
            let errors = document.querySelector('#cnrs-dm-mission-form-errors');
            if (errors) {
                errors.remove();
            }
            const isValid = validateMissionForm();
            if (isValid.length > 0) {
                let html = '<ul id="cnrs-dm-mission-form-errors">';
                for (let i = 0; i < isValid.length; i++) {
                    if (errorMessagesMissionForm[isValid[i]] !== undefined) {
                        html += `<li>${errorMessagesMissionForm[isValid[i]]}</li>`;
                    }
                }
                html += '</ul>';
                document.querySelector('.cnrs-dm-tab-content[data-content="builder"]').insertAdjacentHTML('afterbegin', html);
            } else {
                missionFormFinalInput.value = JSON.stringify(missionForm);
                missionFormFinal.submit();
            }
        }
    }

    if (missionFormPage) {
        const formData = new FormData();
        const url = '/wp-admin/admin-ajax.php';
        formData.append('action', 'get_agents_list');
        const options = {
            method: 'POST',
            body: formData,
        };
        fetch(url, options)
            .then(
                response => response.json()
            ).then(
            success => retrieveAgents(success.data)
        ).catch(
            error => retrieveAgents({error: error, data: null})
        );
    }

    if (adminFormLinkCopy) {
        adminFormLinkCopy.addEventListener('click', function (){
            navigator.clipboard.writeText(this.dataset.link);
        });
    }

    if (addManagerButton) {
        addManagerButton.addEventListener('click', function (){
            const formData = new FormData();
            const url = '/wp-admin/admin-ajax.php';
            const iteration = document.querySelectorAll('.cnrs-dm-manager-block').length + 1;
            formData.append('action', 'get_new_manager');
            formData.append('iteration', iteration);
            const options = {
                method: 'POST',
                body: formData,
            };
            fetch(url, options)
                .then(
                    response => response.json()
                ).then(
                success => addNewManager(success.data)
            ).catch(
                error => addNewManager({error: error, data: null})
            );
        });
    }

    if (addAdminEmailButton) {
        addAdminEmailButton.onclick = function (){
            const fieldsLength = document.querySelector('#cnrs-dm-admin-emails-wrapper').children.length;
            const newInput = `<label for="cnrs-dm-admin-email-${fieldsLength}" class="cnrs-dm-admin-email-with-trash"><input name="cnrs-dm-admin-email[]" autocomplete="off" spellcheck="false" type="email" id="cnrs-dm-admin-email-${fieldsLength}" class="regular-text"><span class="cnrs-dm-remove-admin-emails-button cnrs-dm-tool-button" data-action="remove-email"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg></span></label>`;
            adminEmailsWrapper.insertAdjacentHTML('beforeend', newInput);
            removeAdminEmailListeners();
        }
    }
    removeAdminEmailListeners();
}

function removeAdminEmailListeners() {
    const btns = document.querySelectorAll('.cnrs-dm-remove-admin-emails-button');
    for (let i = 0; i < btns.length; i++) {
        btns[i].onclick = function (){
            this.closest('label').remove();
        };
    }
}

function addNewManager(info) {
    if (info.error === null) {
        const html = info.data;
        managerList.insertAdjacentHTML('afterbegin', html);
        deleteManagerButtonsAction();
    } else {
        console.log(info.error)
    }
}

function deleteManagerButtonsAction() {
    const deleteBtns = document.querySelectorAll('.cnrs-dm-manager-delete-button');
    for (let i = 0; i < deleteBtns.length; i++) {
        deleteBtns[i].onclick = function () {
            this.closest('.cnrs-dm-manager-block').remove();
        }
    }
}

function retrieveAgents(info) {
    if (info.error === null) {
        agentsList = info.data;
        buildFormList();
    } else {
        console.log(info.error)
    }
}

function buildFormList(page = 1, search = '', results = 10, status = 'ALL') {
    if (missionListLoader) {
        missionListLoader.classList.add('show');
        const formData = new FormData();
        const url = '/wp-admin/admin-ajax.php';
        formData.append('action', 'get_forms_list');
        formData.append('agents', JSON.stringify(agentsList));
        formData.append('page', page);
        formData.append('search', search);
        formData.append('results_per_page', results);
        formData.append('status_filter', status);
        const options = {
            method: 'POST',
            body: formData,
        };
        fetch(url, options)
            .then(
                response => response.json()
            ).then(
            success => insertListInHTML(success.data)
        ).catch(
            error => insertListInHTML({error: error, data: null, html: ''})
        );
    }
}

function insertListInHTML(info) {
    if (info.error === null) {
        missionFormListContainer.innerHTML = '';
        missionFormListContainer.insertAdjacentHTML('beforeend', info.html);
        setListListener();
        missionListLoader.classList.remove('show');
        missionFormTotal.innerHTML = '(' + info.data.total + ')';
    } else {
        console.log(info.error);
    }
}

function setListListener() {
    const searchInput = document.querySelector('#cnrs-data-manager-mission-search');
    const searchBtn = document.querySelector('#cnrs-data-manager-search-submit');
    const nbOfResult1 = document.querySelector('#cnrs-data-manager-limit-1');
    const nbOfResult2 = document.querySelector('#cnrs-data-manager-limit-2');
    const statusResult1 = document.querySelector('#cnrs-data-manager-status-1');
    const statusResult2 = document.querySelector('#cnrs-data-manager-status-2');
    const currentPage = document.querySelector('#current-page-selector');
    const paginators = document.querySelectorAll('.cnrs-dm-mission-form-pagination-btn');
    const actions = document.querySelectorAll('.cnrs-dm-actions-triggers');

    if (nbOfResult1 && nbOfResult2) {
        nbOfResult1.onchange = function () {nbOfResult2.value = this.value;}
        nbOfResult2.onchange = function () {nbOfResult1.value = this.value;}
    }

    if (statusResult1 && statusResult2) {
        statusResult1.onchange = function () {statusResult2.value = this.value;}
        statusResult2.onchange = function () {statusResult1.value = this.value;}
    }

    const apply = document.querySelectorAll('.cnrs-data-manager-limit-action');
    for (let i = 0; i < apply.length; i++) {
        apply[i].onclick = function () {
            if (searchInput && nbOfResult1 && nbOfResult2) {
                let search = searchInput.value;
                let status = statusResult1.value;
                let limit = nbOfResult1.value;
                let current = currentPage ? currentPage.value : 1;
                buildFormList(current, search, limit, status);
            }
        }
    }
    if (searchBtn) {
        searchBtn.onclick = function () {
            let search = searchInput.value;
            let limit = nbOfResult1 ? nbOfResult1.value : 10;
            let status = statusResult1 ? statusResult1.value : 'ALL';
            let current = currentPage ? currentPage.value : 1;
            buildFormList(current, search, limit, status);
        }
    }
    for (let i = 0; i < paginators.length; i++) {
        paginators[i].onclick = function () {
            if (searchInput && statusInput) {
                let search = searchInput.value;
                let status = statusResult1.value;
                let limit = nbOfResult1.value;
                let current = this.dataset.page;
                buildFormList(current, search, limit, status);
            }
        }
    }
    const expandBtns = document.querySelectorAll('.toggle-row');
    for (let i = 0; i < expandBtns.length; i++) {
        expandBtns[i].onclick = function () {
            const parent = this.closest('tr');
            if (parent.classList.contains('is-expanded')) {
                parent.classList.remove('is-expanded');
            } else {
                parent.classList.add('is-expanded');
            }
        }
    }

    for (let i = 0; i < actions.length; i++) {
        if (!actions[i].classList.contains('disabled')) {
            actions[i].onclick = function () {
                missionListLoader.classList.add('show');
                const formData = new FormData();
                const url = '/wp-admin/admin-ajax.php';
                formData.append('action', 'form_list_action');
                formData.append('trigger', this.dataset.action);
                formData.append('form_id', this.dataset.form);
                const options = {
                    method: 'POST',
                    body: formData,
                };
                fetch(url, options)
                    .then(
                        response => response.json()
                    ).then(
                    success => refreshListTab(success.data)
                ).catch(
                    error => refreshListTab({error: error, data: null})
                );
            }
        }
    }
}

function refreshListTab(info) {
    if (info.error === null) {
        window.location.reload(true);
    } else {
        console.warn(info.error);
    }
}

function validateMissionForm() {
    let errors = [];
    if (missionForm.title.length < 1) {
        errors.push('form-title');
    }
    for (let i = 0; i < missionForm.elements.length; i++) {
        let element = missionForm.elements[i];
        if (element.label.length < 1 && element.type !== 'comment') {
            errors.push(element.type);
        }
        if (['checkbox', 'radio', 'signs'].includes(element.type) && (element.data.choices === null || element.data.choices.length === 0)) {
            errors.push(element.type + '-choices');
        }
    }
    return errors;
}

function resizeTextAreaOnLoad() {
    missionFormTitle.style.height = 'auto';
    missionFormTitle.style.height = missionFormTitle.scrollHeight + 'px';
    if (missionFormTitle.value.substr(0, missionFormTitle.selectionStart).split("\n").length > 1) {
        missionFormTitle.style.marginBottom = 34 + 'px';
    } else {
        missionFormTitle.style.marginBottom = 0 + 'px';
    }
}

function addFormTool(info) {
    if (info.error === null) {
        missionFormStructure.insertAdjacentHTML('beforeend', info.data);
        setToolsListeners();
        if (info.html !== null) {
            if (document.querySelector('#cnrs-dm-form-modal-wrapper')) {
                document.querySelector('#cnrs-dm-form-modal-wrapper').remove();
            }
            adminWrapper.insertAdjacentHTML('beforeend', info.html);
            let modalWrapper = document.querySelector('#cnrs-dm-form-modal-wrapper');
            modalWrapper.style.width = wpContainerWidth + 'px';
            modalWrapper.style.left = (window.innerWidth - wpContainerWidth) + 'px';
            setTimeout(function () {
                modalWrapper.classList.add('display');
            }, 50);
            const cancelBtn = document.querySelector('#cnrs-dm-form-button-cancel');
            if (cancelBtn) {
                cancelBtn.onclick = function () {
                    closeModalWrapper();
                }
            }
            const saveBtn = document.querySelector('#cnrs-dm-form-button-save');
            if (saveBtn) {
                saveBtn.onclick = function () {
                    saveToolSettings();
                }
            }

            const addChoices = document.querySelectorAll('.cnrs-dm-form-add-choice');
            for (let i = 0; i < addChoices.length; i++) {
                addChoices[i].onclick = function() {
                    const container = document.querySelector('#cnrs-dm-form-modal-choices');
                    const modal = document.querySelector('#cnrs-dm-form-modal-wrapper');
                    if (container) {
                        let html = `<label>
                                <input type="text" spellcheck="false" name="cnrs-dm-form-modal-choice[]">
                                <span class="cnrs-dm-form-remove-choice">-</span>
                            </label>`;
                        if (modal.dataset.comment !== undefined) {
                            html += `<label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-other">
                                <input type="checkbox" name="cnrs-dm-other-option" value="other">
                                <span>${modal.dataset.comment}</span>
                            </label>`;
                        }
                        container.insertAdjacentHTML('beforeend', `<li class="cnrs-dm-form-modal-choice">${html}</li>`);
                        const delBtns = document.querySelectorAll('.cnrs-dm-form-remove-choice');
                        for (let j = 0; j < delBtns.length; j++) {
                            delBtns[j].onclick = function () {
                                this.closest('.cnrs-dm-form-modal-choice').remove();
                            }
                        }
                    }
                }
            }
        }
        missionForm.elements.push(JSON.parse(info.json));
        refreshFormPreview();
    } else {
        console.error(info.error);
    }
}

function getToolModal(info) {
    if (info.error === null) {
        if (info.data !== null) {
            if (document.querySelector('#cnrs-dm-form-modal-wrapper')) {
                document.querySelector('#cnrs-dm-form-modal-wrapper').remove();
            }
            adminWrapper.insertAdjacentHTML('beforeend', info.data);
            let modalWrapper = document.querySelector('#cnrs-dm-form-modal-wrapper');
            modalWrapper.style.width = wpContainerWidth + 'px';
            modalWrapper.style.left = (window.innerWidth - wpContainerWidth) + 'px';
            setTimeout(function () {
                modalWrapper.classList.add('display');
            }, 50);
            const cancelBtn = document.querySelector('#cnrs-dm-form-button-cancel');
            if (cancelBtn) {
                cancelBtn.onclick = function () {
                    closeModalWrapper();
                }
            }
            const saveBtn = document.querySelector('#cnrs-dm-form-button-save');
            if (saveBtn) {
                saveBtn.onclick = function () {
                    saveToolSettings();
                }
            }

            const addChoices = document.querySelectorAll('.cnrs-dm-form-add-choice');
            for (let i = 0; i < addChoices.length; i++) {
                addChoices[i].onclick = function() {
                    const container = document.querySelector('#cnrs-dm-form-modal-choices');
                    const modal = document.querySelector('#cnrs-dm-form-modal-wrapper');
                    if (container) {
                        let html = `<label>
                                <input type="text" spellcheck="false" name="cnrs-dm-form-modal-choice[]">
                                <span class="cnrs-dm-form-remove-choice">-</span>
                            </label>`;
                        if (modal.dataset.comment !== undefined) {
                            html += `<label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-other">
                                <input type="checkbox" name="cnrs-dm-other-option" value="other">
                                <span>${modal.dataset.comment}</span>
                            </label>`;
                        }
                        container.insertAdjacentHTML('beforeend', `<li class="cnrs-dm-form-modal-choice">${html}</li>`);
                        const delBtns = document.querySelectorAll('.cnrs-dm-form-remove-choice');
                        for (let j = 0; j < delBtns.length; j++) {
                            delBtns[j].onclick = function () {
                                this.closest('.cnrs-dm-form-modal-choice').remove();
                            }
                        }
                    }
                }
            }
            const delBtns = document.querySelectorAll('.cnrs-dm-form-remove-choice');
            for (let j = 0; j < delBtns.length; j++) {
                delBtns[j].onclick = function () {
                    this.closest('.cnrs-dm-form-modal-choice').remove();
                }
            }

            let config = {...tinyMCEConfig};
            config.selector = '#cnrs-dm-tinymce'
            tinymce.remove();
            tinymce.init(config);
        }
    } else {
        console.error(info.error);
    }
}

function saveToolSettings() {
    const elmt = document.querySelector('#cnrs-dm-form-modal');
    const element = {'type': '', 'label': '', 'data': {'value': null, 'values': null, 'choices': null, 'required': false, 'tooltip': '', 'isReference': false}};
    const iteration = document.querySelector('input[name="cnrs-dm-iteration"]').value;
    let choicesList = [];

    element.type = document.querySelector('input[name="cnrs-dm-type"]').value;
    if (document.querySelector('input[name="cnrs-dm-label"]')) {
        if (element.type === 'title') {
            element.data.value = [document.querySelector('input[name="cnrs-dm-label"]').value];
        }
        element.label = document.querySelector('input[name="cnrs-dm-label"]').value;
    }
    if (document.querySelector('textarea[name="cnrs-dm-comment"]')) {
        element.data.value = [tinymce.get('cnrs-dm-tinymce').getContent().replaceAll("\n", '<br/>')];
    }
    const choices = document.querySelectorAll('.cnrs-dm-form-modal-choice');
    for (let i = 0; i < choices.length; i++) {
        const input = choices[i].querySelector('input[type="text"]');
        const needsComment = choices[i].querySelector('input[type="checkbox"]');
        const appendix = needsComment !== null && needsComment.checked === true ? '-opt-comment' : '';
        choicesList.push(input.value + appendix);
    }
    element.data.choices = choicesList.length > 0 ? choicesList : null;
    const required = document.querySelector('input[name="cnrs-dm-required-option"]');
    element.data.required = required ? required.checked : false;
    const tooltip = document.querySelector('textarea[name="cnrs-dm-tooltip"]');
    if (tooltip) {
        element.data.tooltip = tooltip.value.replaceAll("\n", '<br/>');
    }
    const isReference = document.querySelector('input[name="cnrs-dm-isReference"]')
    if (isReference) {
        element.data.isReference = isReference.value === '1';
    }
    missionForm.elements[iteration] = element;
    refreshFormPreview();
    closeModalWrapper();
}

function setToolsListeners(refresh = false) {
    const editBtn = document.querySelectorAll('.cnrs-dm-tool-button[data-action="edit"]');
    const deleteBtn = document.querySelectorAll('.cnrs-dm-tool-button[data-action="delete"]');
    const tools = document.querySelectorAll('.cnrs-dm-form-tool-render');
    const movers = document.querySelectorAll('.cnrs-dm-mission-form-mover');
    const isReference = document.querySelectorAll('input[name="cnrs-dm-form-tool-is-reference-input"]');

    for (let i = 0; i < editBtn.length; i++) {
        const btn = editBtn[i];
        btn.onclick = function() {
            const tool = this.closest('.cnrs-dm-form-tool-render');
            const index = parseInt(tool.dataset.index);
            const error = document.querySelector('#cnrs-dm-form-tools').dataset.error;
            const formData = new FormData();
            const url = '/wp-admin/admin-ajax.php';
            formData.append('action', 'get_form_tool');
            formData.append('tool', this.closest('.cnrs-dm-form-tool-render').dataset.type);
            formData.append('json', JSON.stringify(missionForm.elements[index]));
            formData.append('iteration', index);
            const options = {
                method: 'POST',
                body: formData,
            };
            fetch(url, options)
                .then(
                    response => response.json()
                ).then(
                success => getToolModal(success.data)
            ).catch(
                error => getToolModal({error: error, data: null})
            );
        }
    }

    for (let i = 0; i < deleteBtn.length; i++) {
        const btn = deleteBtn[i];
        btn.onclick = function() {
            const tool = this.closest('.cnrs-dm-form-tool-render');
            const index = parseInt(tool.dataset.index);
            if (missionForm.elements[index]) {
                missionForm.elements.splice(index, 1);
            }
            tool.remove();
            resetFormIterations();
            refreshFormPreview();
        }
    }

    for (let i = 0; i < movers.length; i++) {
        movers[i].onclick = function () {
            const parent = this.closest('.cnrs-dm-form-tool-render');
            let iteration = parseInt(parent.dataset.index);
            const originalIteration = iteration;
            const first = iteration === 0;
            const last = iteration === tools.length - 1;
            const elements = [...missionForm.elements];
            const elmt = elements[originalIteration];
            if (this.dataset.action === 'up' && first === false) {
                iteration--;
                missionFormStructure.insertBefore(parent, parent.previousElementSibling);
            } else if (this.dataset.action === 'down' && last === false) {
                iteration++;
                insertAfter(parent, parent.nextElementSibling);
            }
            const renders = document.querySelectorAll('.cnrs-dm-form-tool-render');
            for (let j = 0; j < renders.length; j++) {
                renders[j].dataset.index = j;
            }

            elements.splice(originalIteration, 1);
            let reorder = [];
            for (let j = 0; j < elements.length; j++) {
                if (j === iteration) {
                    reorder.push(elmt);
                }
                reorder.push(elements[j]);
            }
            missionForm.elements = reorder;
            refreshFormPreview();
        }
    }

    for (let i = 0; i < isReference.length; i++) {
        isReference[i].oninput = function () {
            if (this.checked === true) {
                const iteration = parseInt(this.closest('.cnrs-dm-form-tool-render').dataset.index);
                const elements = [...missionForm.elements];
                for (let j = 0; j < elements.length; j++) {
                    elements[j].data.isReference = false;
                }
                missionForm.elements = elements;
                missionForm.elements[iteration].data.isReference = true;
                refreshFormPreview();
            }
        }
    }

    if (refresh === true && tools.length > 0) {
        refreshFormPreview();
    }
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function resetFormIterations() {
    const tools = document.querySelectorAll('.cnrs-dm-form-tool-render');
    for (let i = 0; i < tools.length; i++) {
        tools[i].dataset.index = i;
    }
}

function closeModalWrapper() {
    const modal = document.querySelector('#cnrs-dm-form-modal-wrapper');
    modal.classList.remove('display');
    let config = {...tinyMCEConfig};
    config.selector = '#cnrs-dm-tinymce'
    tinymce.remove();
    tinymce.init(config);
    setTimeout(function () {
        modal.remove();
    }, 250);
}

function refreshFormPreview() {
    let html = '';
    const d = new Date;
    const addSomeChoices = missionFormPreview.dataset.choices;
    const addSomePads = missionFormPreview.dataset.pads;
    const signHere = missionFormPreview.dataset.sign;
    for (let i = 0; i < missionForm.elements.length; i++) {
        const element = missionForm.elements[i];
        const required = typeof element.data !== "undefined" && element.data.required === true
            ? ' class="cnrs-dm-required"'
            : '';
        const tooltip = typeof element.data !== "undefined" && typeof element.data.tooltip !== "undefined" && element.data.tooltip.length > 0
            ? `<span class="cnrs-dm-tooltip-icon" title="${element.data.tooltip.replaceAll('<br/>', ' ')}">?</span>`
            : '';
        html += '<div class="cnrs-dm-preview-elements">';
        if (element.type === 'checkbox') {
            html += `<div class="cnrs-dm-form-preview-label"><span${required}>${element.label} ${tooltip}</span>`;
            if (element.data.choices === null || element.data.choices.length === 0) {
                html += `<i>${addSomeChoices}</i>`;
            } else {
                for (let j = 0; j < element.data.choices.length; j++) {
                    let label = element.data.choices[j].replace('-opt-comment', '');
                    html += '<label>';
                    html += `<input type="checkbox">`;
                    html += `<span>${label}</span>`;
                    html += '</label>';
                    if (label + '-opt-comment' === element.data.choices[j]) {
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                    }
                }
            }
            html += `</div>`;
        } else if (element.type === 'comment') {
            html += `<div class="cnrs-dm-form-preview-comment">${element.data.value[0] ?? ''}</div>`;
        } else if (element.type === 'input') {
            html += `<label><span${required}>${element.label} ${tooltip}</span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `</label>`;
        } else if (element.type === 'number') {
            let split = element.label.split(';');
            let label = split[0];
            let unit = typeof split[1] !== "undefined" ? '<span class="cnrs-dm-form-unit">' +  split[1] + '</span>' : '';
            html += `<label><span${required}>${label} ${tooltip}</span>`;
            html += `<div class="cnrs-dm-number-field"><span class="cnrs-dm-filled" data-type="number">
                ${Math.floor(Math.random() * 10)}
                <span class="cnrs-dm-carets">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M182.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M182.6 470.6c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128z"/></svg>
                </span>
            </span>${unit}</div>`;
            html += `</label>`;
        } else if (element.type === 'date') {
            html += `<label><span${required}>${element.label} ${tooltip}`;
            if (element.data.isReference === true) {
                html += `<span class="cnrs-dm-preview-reference-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="12" height="12"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>
                </span>`;
            }
            html += `</span>`;
            html += `<span class="cnrs-dm-filled" data-type="date">
                ${('0' + d.getDate()).slice(-2)}/${('0' + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64C28.7 64 0 92.7 0 128v16 48V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H344V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM48 192H400V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192z"/></svg>
                </span>
            </span>`;
            html += `</label>`;
        } else if (element.type === 'time') {
            html += `<label><span${required}>${element.label} ${tooltip}</span>`;
            html += `<span class="cnrs-dm-filled" data-type="time">
                ${('0' + d.getHours()).slice(-2)}:${('0' + d.getMinutes()).slice(-2)}
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                </span>
            </span>`;
            html += `</label>`;
        } else if (element.type === 'datetime') {
            html += `<label><span${required}>${element.label} ${tooltip}</span>`;
            html += `<span class="cnrs-dm-filled" data-type="datetime">
                ${('0' + d.getDate()).slice(-2)}/${('0' + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}&nbsp;
                ${('0' + d.getHours()).slice(-2)}:${('0' + d.getMinutes()).slice(-2)}
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm80 64c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80z"/></svg>
                </span>
            </span>`;
            html += `</label>`;
        } else if (element.type === 'radio') {
            html += `<div class="cnrs-dm-form-preview-label"><span${required}>${element.label} ${tooltip}</span>`;
            if (element.data.choices === null || element.data.choices.length === 0) {
                html += `<i>${addSomeChoices}</i>`;
            } else {
                for (let j = 0; j < element.data.choices.length; j++) {
                    let label = element.data.choices[j].replace('-opt-comment', '');
                    html += '<label>';
                    html += `<input type="radio" name="checkbox-${i}">`;
                    html += `<span>${label}</span>`;
                    html += '</label>';
                    if (label + '-opt-comment' === element.data.choices[j]) {
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                        html += `<span class="cnrs-dm-suspensions"></span>`;
                    }
                }
            }
            html += `</div>`;
        } else if (element.type === 'separator') {
            html += '<hr/>';
        } else if (element.type === 'textarea') {
            html += `<label><span${required}>${element.label} ${tooltip}</span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `<span class="cnrs-dm-suspensions"></span>`;
            html += `</label>`;
        } else if (element.type === 'title') {
            html += `<h4>${element.data.value[0] ?? ''}</h4>`;
        } else if (element.type === 'signs') {
            html += `<div class="cnrs-dm-form-preview-label"><span${required}>${element.label}</span>`;
            if (element.data.choices === null || element.data.choices.length === 0) {
                html += `<i>${addSomePads}</i>`;
            } else {
                html += '<div class="cnrs-dm-sign-wrapper">';
                for (let j = 0; j < element.data.choices.length; j++) {
                    let choice = element.data.choices[j].split(';');
                    html += '<div class="cnrs-dm-sign-pad">';
                    for (let k = 0; k < choice.length; k++) {
                        html += `<span${k === 0 ? ' class="cnrs-dm-pad-first-line"' : ''}>${choice[k]}</span>`;
                    }
                    html += `<div class="cnrs-dm-sign-canvas"><p>${signHere}</p></div>`;
                    html += '</div>';
                }
                html += '</div>';
            }
            html += '</div>';
        }
        html += '</div>';
    }
    missionFormPreview.innerHTML = html;
    
    const tools = document.querySelectorAll('.cnrs-dm-form-tool-render');
    const previewElements = document.querySelectorAll('.cnrs-dm-preview-elements');
    for (let i = 0; i < tools.length; i++) {
        tools[i].onmouseover = function () {
            previewElements[i].classList.add('locate');
        }
        tools[i].onmouseleave = function () {
            previewElements[i].classList.remove('locate');
        }
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
            form.querySelector('svg').classList.remove('hide');
            const formData = new FormData();
            const url = '/wp-admin/admin-ajax.php';
            formData.append('file', xlsFile);
            formData.append('data', JSON.stringify(xlsArray));
            formData.append('action', 'import_xml_file');
            formData.append('team', fileImportTeamSelector.value);
            const options = {
                method: 'POST',
                body: formData,
            };
            fetch(url, options)
                .then(
                    response => response.json()
                ).then(
                success => getImportResponse(success.data)
            ).catch(
                error => getImportResponse({error: fileImportInput.dataset.ko, data: null})
            );
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

function getImportResponse(data) {
    const info = data.data;
    const error = data.error;
    let message = '';
    document.querySelector('#cnrs-dm-import-confirm-form').remove();
    if (error !== null) {
        message = `<p class="cnrs-dm-import-error-message">${error}</p>`;
    } else {
        message = `<p class="cnrs-dm-import-ok-message">${fileImportInput.dataset.ok}</p>`;
        message += info;
    }
    importResponseStateContainer.innerHTML += message;
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
        if (mainLng.value.length > 3 && regexLng.test(mainLng.value)) {
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
            if (markerLng.value.length > 3 && regexLng.test(markerLng.value)) {
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
                    && regexLng.test(lng.value))
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
