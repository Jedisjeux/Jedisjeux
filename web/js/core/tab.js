$(function() {
    var hash = window.location.hash;

    // do some validation on the hash here

    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
});