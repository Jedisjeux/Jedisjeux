$(function() {

    if ($("#form_mechanism").length > 0) {
        initCombobox();
    }

    function initCombobox() {
        $("#form_mechanism").chosen({
            no_results_text: "Aucun mécanisme ne correspond",
            placeholder_text_multiple: "choisir des mécanismes"
        });

        $("#form_theme").chosen({
            no_results_text: "Aucun thème ne correspond",
            allow_single_deselect: true,
            placeholder_text_single: "choisir un thème"
        });
    }

});


