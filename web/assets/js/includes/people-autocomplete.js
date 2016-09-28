$(function () {

  "use strict";

  $(".peopleAutocomplete").select2({
    ajax: {
      url: Routing.generate('app_api_person_index'),
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          criteria: {
            query: params.term // search term
          },
          page: params.page
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;

        return {
          results: data._embedded.items,
          pagination: {
            more: (params.page * 30) < data.total
          }
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) {
      return markup;
    }, // let our custom formatter work
    minimumInputLength: 1,
    templateResult: formatRepo, // omitted for brevity, see the source of this page
    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
  });

  function formatRepo(repo) {
    if (repo.loading) return repo.text;

    var markup =
      "<div class='clearfix'>" +
        "<div class='pull-left'><img style='width: 50px; height: 50px; margin-right: 10px;' class='img-responsive img-round' src='" + getImage(repo) + "' /></div>" +
        "<div>" + repo.full_name + "</div>";
      "</div>";

    return markup;
  }

  function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
  }

  function getImage(repo) {
    if (repo.image) {
      return repo.image.default;
    } else {
      return '//ssl.gstatic.com/accounts/ui/avatar_2x.png';
    }
  }

});