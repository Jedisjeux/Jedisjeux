$(function() {

    if ($("#form_mechanism").length > 0) {
        initCombobox();

        $(".begginer").click(function() {
            $("#form_cible").val(1);
            $("#form_cible").form().submit();
        });

        $(".intermediate").click(function() {
            $("#form_cible").val(2);
            $("#form_cible").form().submit();
        });

        $(".master").click(function() {
            $("#form_cible").val(3);
            $("#form_cible").form().submit();
        });
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


