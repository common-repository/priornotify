if (typeof priorNotify_Config === 'undefined') priorNotify_Config = { messages: {} };

function priorNotify_ready() {
    var allSelects = document.querySelectorAll("[id*='form-select-']");
    var wooCommerceSelected = false;
    var latestTarget = null, latestSelected = null;
    var currentTarget = null;
    allSelects.forEach(function (elem) {
        if (elem.value === wooCommerceForm.id) wooCommerceSelected = true;
        elem.addEventListener('change', formChange);
        elem.addEventListener('focus', focusForm);
    });

    function getWidgetId(elem) {
        return elem.id.replace('form-select-', '');
    }

    function focusForm(e) {
        latestTarget = e.target;
        latestSelected = e.target.value;
    }

    function formChange(e) {
        for (var i = 0; i < e.target.children.length; i++ ) {
            if (e.target.children[i].value === latestSelected) e.target.children[i].removeAttribute('selected');
        }
        if (e.target.value === wooCommerceForm.id) {
            if (wooCommerceSelected) {
                currentTarget = e.target;
                showModal();
                return;
            } 
        }
        
        focusForm(e);
        allSelects = document.querySelectorAll("[id*='form-select-']");
        wooCommerceSelected = false;
        allSelects.forEach(function (elem) {
            if (elem.value === wooCommerceForm.id) wooCommerceSelected = true;
        });
        for (var i = 0; i < e.target.length; i++ ) {
            if (e.target.value === e.target.children[i].value) e.target.children[i].setAttribute('selected', 'true');
        }
        updateForm(e.target.value, getWidgetId(e.target));
    }

    function updateForm(formId, widgetId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var h2 = document.createElement('H2');
                h2.setAttribute('class', 'success-message');
                h2.innerHTML = priorNotify_Config.messages.containerAttached;
                var successContainer = document.getElementsByClassName('success-container')[0];
                successContainer.appendChild(h2);
                setTimeout(function () {
                    h2.parentElement.removeChild(h2);
                }, 3000);
            }
        };
        xhttp.open("POST", "", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("form_id=" + formId + "&widget_id=" + widgetId);
    }

    

    // --- Modal
    var _targettedModal = document.getElementById('sample-modal'),
    _dismiss = document.querySelectorAll('[data-modal-dismiss]'),
    _confirms = document.querySelectorAll('[data-modal-confirm]'),
    modalActiveClass = "is-modal-active";

    function showModal(){
        _targettedModal.classList.add(modalActiveClass);
    }

    function hideModal(event){
        if(event === undefined || event.target.hasAttribute('data-modal-dismiss')) {
            _targettedModal.classList.remove(modalActiveClass);
            latestTarget.value = latestSelected;
        }
    }

    function unselectWooCommerce() {
        allSelects.forEach(function (elem) {
            if (elem.id !== latestTarget.id && elem.value === wooCommerceForm.id) {
                for (var i = 0; i < elem.children.length; i++) {
                    if (elem.children[i].value === wooCommerceForm.id) {
                        elem.children[i].removeAttribute('selected');
                        updateForm(0, getWidgetId(elem));
                    }
                }
                elem.children[0].setAttribute('selected', 'true');
            }
        })
    }

    function confirmModal(event) {
        unselectWooCommerce();
        updateForm(wooCommerceForm.id, getWidgetId(currentTarget));
        _targettedModal.classList.remove(modalActiveClass);
        for (var i = 0; i < currentTarget.children.length; i++) {
            if (currentTarget.children[i].value == currentTarget.value) currentTarget.children[i].setAttribute('selected', 'true');
        }
    }

    function bindEvents(el, callback){
        for (i = 0; i < el.length; i++) {
            (function(i) {
                el[i].addEventListener('click', function(event) {
                    callback(this, event);
                });
            })(i);
        }   
    }

    function dismissModal(){
        bindEvents(_dismiss, function(that){
            hideModal(event);
        });
    }

    function acceptModal() {
        bindEvents(_confirms, function (that) {
            confirmModal(event);
        });
    }

    function initModal(){
        dismissModal();
        acceptModal();
    }

    initModal();


}

document.addEventListener("DOMContentLoaded", priorNotify_ready);
