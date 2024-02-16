/*********************************************************************************************
 *                          CNRS Data Manager Pagination for All JS                          *
 ********************************************************************************************/

const silentLoader = document.querySelector('#cnrs-dm-front-loader-wrapper');
const loaderIcon = document.querySelector('#cnrs-dm-front-loader-icon');

window.addEventListener('load', function (){

    if (!silentLoader && loaderIcon) {

        let loaderWrapper = document.createElement('div');
        loaderWrapper.setAttribute('id', 'cnrs-dm-front-loader-wrapper');
        loaderWrapper.className = 'hide';
        loaderWrapper.appendChild(loaderIcon);
        loaderIcon.removeAttribute('style');
        document.body.appendChild(loaderWrapper);
        prepareListeners();
    }
});

function prepareListeners() {

    let form = document.querySelector('.cnrs-dm-front-filters-wrapper');
    let links = document.querySelectorAll('.cnrs-dm-front-pagination-wrapper [class^="cnrs-dm-front-pagination-"]');

    if (form && links.length > 0) {

        form.onsubmit = function (e) {
            e.preventDefault();
            let inputs = this.querySelectorAll('[name]');
            let json = {};

            for (let i = 0; i < inputs.length; i++) {
                json[inputs[i].name] = inputs[i].value;
            }

            let uri = window.location.pathname + '?' + serialise(json);
            sendRequest(uri);
        }

        for (let i = 0; i < links.length; i++) {
            links[i].onclick = function (e) {
                e.preventDefault();
                let uri = this.href;
                if (uri !== undefined) {
                    let split = this.href.split(window.location.pathname);

                    if (split[1]) {
                        uri = window.location.pathname + this.href.split(window.location.pathname)[1];
                        sendRequest(uri);
                    }
                }
            }
        }
    }
}

function sendRequest(uri) {
    const loader = document.querySelector('#cnrs-dm-front-loader-wrapper');
    loader.classList.remove('hide');
    fetch(uri)
        .then(
            response => response.text()
        ).then(
        success => getFetchHTML(success, uri, false)
    ).catch(
        error => getFetchHTML(error, uri, true)
    );
}

function getFetchHTML(data, uri, error) {

    const loader = document.querySelector('#cnrs-dm-front-loader-wrapper');
    if (error === false) {

        let tmp = document.createElement('html');
        tmp.innerHTML = data;

        let content = tmp.querySelector('.cnrs-dm-front-all-agents-container').innerHTML;
        let pagination = tmp.querySelector('.cnrs-dm-front-pagination-wrapper');
        let currentWrapper = document.querySelectorAll('.cnrs-dm-front-pagination-wrapper');

        for (let i = 0; i < currentWrapper.length; i++) {
            if (pagination) {
                currentWrapper[i].innerHTML = pagination.innerHTML;
            } else {
                currentWrapper[i].innerHTML = '';
            }
        }
        document.querySelector('.cnrs-dm-front-all-agents-container').innerHTML = content;

        const nextURL = uri;
        const nextTitle = document.querySelector('head title').innerText;
        const nextState = {};
        window.history.pushState(nextState, nextTitle, nextURL);

        prepareListeners();

    } else {

        console.log(data);
    }
    loader.classList.add('hide');
}

function serialise(obj) {
    serialised = '';
    Object.keys(obj).forEach(function(key) {
        serialised += encodeURIComponent(key).replace(/%20/g, '+') + '=' + encodeURIComponent(obj[key]).replace(/%20/g, '+') + '&';
    });
    return serialised.slice(0, -1);
}