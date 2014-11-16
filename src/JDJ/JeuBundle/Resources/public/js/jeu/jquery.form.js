/**
 * Created by loic_425 on 22/06/2014.
 */
$(function(){

    if ($("form[name=jdj_jeubundle_jeu]").length > 0) {
        /**
         * init value
         */
        selectJoueurMaxChangeEvent($("#select-joueur-max"));

        $("#select-joueur-max").change(function(){
            selectJoueurMaxChangeEvent(this);
        });

        $("#jdj_jeubundle_jeu_mechanisms").chosen({
            no_results_text: "Aucun mécanisme ne correspond",
            placeholder_text_multiple: "choisir des mécanismes"
        });

        $("#jdj_jeubundle_jeu_themes").chosen({
            no_results_text: "Aucun thème ne correspond",
            allow_single_deselect: true,
            placeholder_text_multiple: "choisir des thèmes"
        });

    }

    /**
     * Permet les changements du nombre de joueur max en fonction du combobox
     *
     * @param that select object
     */
    function selectJoueurMaxChangeEvent(that) {
        var joueurMinValue = $("#jdj_jeubundle_jeu_joueurMin").val();

        switch ($(that).val()){
            case 'identical':
                $(".joueurMax").hide();
                $("#jdj_jeubundle_jeu_joueurMax").val(joueurMinValue);
                break;
            case 'minimal':
                $(".joueurMax").hide();
                $("#jdj_jeubundle_jeu_joueurMax").val("");
                break;
            case 'custom':
                $(".joueurMax").show();
                break;
        }
    }
});

