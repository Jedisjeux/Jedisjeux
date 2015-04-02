/**
 * Created by mfoussette on 06/02/2015.
 */
/**
 * Démarrage du spinner
 */
function startSpinner() {
    $('.loading').show().addClass('display-flex');
}

/**
 * Arrêt du spinner
 */
function stopSpinner() {
    $('.loading').hide();
}

/**
 * Gestion du spinner en fonction des appels ajax
 */
function manageAjaxEvent() {
    $(document).ajaxComplete(function (event, request, settings) {
        stopSpinner();
    });

    $(document).ajaxSend(function (event, request, settings) {
        startSpinner();
    });

    $(document).ajaxError(function (event, request, settings) {
        stopSpinner();
    });
}

manageAjaxEvent();

