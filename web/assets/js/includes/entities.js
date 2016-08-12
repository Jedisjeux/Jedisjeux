(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.entity').each(function () {
      var $entity = $(this);

      var id = parseInt($entity.html());
      var entityName = $entity.data('entity');

      if (isNaN(id)) {
        return;
      }

      switch (entityName) {
        case 'game':
          showGame($entity, id);
          break;
      }
    });

    /**
     * @param $entity
     * @param id
     */
    function showGame($entity, id) {
      $.get(Routing.generate('sylius_api_product_show', {'id': id}), function (product) {
        console.log(product);

        $entity.html(
          $('<div>')
            .addClass('magazine-item mag-1')
            .append(
              $('<h4>')
                .append(
                  $('<a>')
                    .attr('href', Routing.generate('sylius_product_show', {'slug': product.slug}))
                    .html(product.name)
                )
            )
            .append(
              $('<div>')
                .addClass('magazine-meta')
                .append(
                  $('<i>')
                    .addClass('fa fa-calendar')
                    .html(' 31 juillet 2016')
                )
            )
        );
      });
    }

    /**
     * <div class="magazine-item mag-1">
     <!-- Image -->
     <a href="/app_dev.php/jeu-de-societe/scythe" title="Scythe">
     <img class="img-responsive" src="http://jdj.dev/media/cache/magazine_item/uploads/img/scythe-1887-1445582553.jpg" alt="Scythe">
     </a>

     <!-- Heading -->
     <h4><a href="/app_dev.php/jeu-de-societe/scythe">Scythe</a></h4>
     <!-- Meta -->
     <div class="magazine-meta">
     <i class="fa fa-calendar"></i> <!-- TODO publishedAt -->31 juillet 2016

     </div>
     <!-- Para -->
     <p>Nemo enim ipsam voluptatem quia quia neque porro qui dolorem ipsum quia dolor sit amet
     consectetur. </p>

     </div>
     */

  });
})(jQuery);