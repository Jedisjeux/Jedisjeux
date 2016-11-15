$(function () {

    var $form = $('form[name=jdj_jeubundle_jeu]');

    if ($form.length > 0) {
        $('#jdj_jeubundle_jeu_mechanisms, #jdj_jeubundle_jeu_themes').multipleSelect({
            filter: true
        });
    }
});