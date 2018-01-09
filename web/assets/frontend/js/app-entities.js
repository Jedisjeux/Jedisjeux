(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.entity').each(function () {
      var $entity = $(this);

      var code = $entity.html();
      var entityName = $entity.data('entity');

      switch (entityName) {
        case 'game':
          showGame($entity, code);
          break;
      }
    });

    /**
     * @param $entity
     * @param code
     */
    function showGame($entity, code) {
      $.get(Routing.generate('sylius_api_product_show', {'code': code}), function (product) {
        $entity.html(
          $('<div>').addClass('img-box-5')
            .append($('<div>').addClass('container')
              .append($('<div>').addClass('img-box-5-item')
                .append($('<div>').addClass('row')
                  .append($('<div>').addClass('col-md-4')
                    .append($('<div>').addClass('img-box-5-img')
                      .append(
                        $('<a>').attr('href', Routing.generate('sylius_frontend_product_show', {'slug': product.slug})).append(
                          $('<img>')
                            .addClass('img-responsive img-thumbnail')
                            .attr('src', product.image.default)
                        )
                      )
                    )
                  )
                  .append($('<div>').addClass('col-md-8')
                    .append($('<div>').addClass('img-box-5-content').append($('<div>').addClass('container')
                        .append($('<div>').addClass('row')
                          .append($('<h4>')
                            .html(product.name)
                          )
                        )
                        .append($('<div>').addClass('row')
                          .append(getShortDescriptionContainer(product))
                        )
                      ).append(
                      $('<div>').addClass('container')
                        .append($('<div>').addClass('row')
                          .append($('<ul>').addClass('list-unstyled')
                            .append($('<li>')
                              .append($('<p>').addClass('pull-left bg-color square-2 rounded-2').append(
                                $('<span>').addClass('fa fa-user white')
                                )
                              ).append($('<p>').addClass('game-attribute')
                                .html(product.min_player_count + ' à ' + product.max_player_count + ' joueurs')
                              )
                            )
                            .append($('<li>')
                              .append($('<p>').addClass('pull-left bg-color square-2 rounded-2').append(
                                $('<span>').addClass('fa fa-child white')
                                )
                              ).append($('<p>').addClass('game-attribute')
                                .html('à partir de ' + product.min_age + ' ans')
                              )
                            )
                            .append(getDurationContainer(product))
                            .append(getMechanismsContainer(product))
                            .append(getThemesContainer(product))
                          )
                        )
                      )
                    )
                  )
                )
              )
            ))
          .append(
            $('<div>')
              .addClass('clearfix')
          );


      });
    }
  });

  /**
   * @param product
   * @returns {*}
   */
  function getDurationContainer(product) {
    if (typeof product.min_duration === 'undefined') {
      return $('<span>');
    }

    return $('<li>')
      .append(
        $('<p>').addClass('pull-left bg-color square-2 rounded-2')
          .append(
            $('<span>')
              .addClass('fa fa-clock-o white')
          )
      )
      .append(
        $('<p>').addClass('game-attribute')
          .html(product.min_duration + ' minutes')
      )
  }

  function getShortDescriptionContainer(product) {
    if (typeof product.short_description === 'undefined') {
      return $('<span>');
    }


    return $('<div>').addClass('quote-one')
      .append($('<div>').addClass('row')
        .append($('<div>').addClass('quote-one-item')
          .append($('<span>').addClass('color').html('“'))
          .append($('<div>').addClass('quote-one-right')
            .append($('<div>').addClass('content hcodeeContent').html(product.short_description))
            .append(
              $('<div>').addClass('show-more')
                .append($('<a>').attr('href', '#').html('Lire la suite...'))
            )
          )
        ));
  }

  /**
   * @param product
   * @returns {*}
   */
  function getMechanismsContainer(product) {
    if (product.mechanisms.length === 0) {
      return $('<span>');
    }

    var $paragraph = $('<p>').addClass('game-attribute');
    var first = true;

    $.each(product.mechanisms, function () {

      if (false === first) {
        $paragraph.append(
          $('<span>').html(', ')
        );
      }

      $paragraph.append(
        $('<a>').attr('href', Routing.generate('sylius_frontend_product_index_by_taxon', {'permalink': this.permalink}))
          .html(this.name)
      );

      first = false;

    });

    return $('<li>')
      .append(
        $('<p>').addClass('pull-left bg-color square-2 rounded-2')
          .append(
            $('<span>')
              .addClass('fa fa-cog white')
          )
      )
      .append($paragraph);
  }

  /**
   * @param product
   * @returns {*}
   */
  function getThemesContainer(product) {
    if (product.themes.length === 0) {
      return $('<span>');
    }

    var $paragraph = $('<p>').addClass('game-attribute');
    var first = true;

    $.each(product.themes, function () {

      if (false === first) {
        $paragraph.append(
          $('<span>').html(', ')
        );
      }

      $paragraph.append(
        $('<a>').attr('href', Routing.generate('sylius_frontend_product_index_by_taxon', {'permalink': this.permalink}))
          .html(this.name)
      );

      first = false;

    });

    return $('<li>')
      .append(
        $('<p>').addClass('pull-left bg-color square-2 rounded-2')
          .append(
            $('<span>')
              .addClass('fa fa-picture-o white')
          )
      )
      .append($paragraph);
  }

})(jQuery);