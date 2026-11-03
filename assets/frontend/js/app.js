import 'bootstrap/dist/js/bootstrap.bundle';
import $ from 'jquery';

import './app-notifications';
import './article-filters';
import './page-views';
import './person-filters';
import './product-list';
import './rating';
import './sylius-form-collection';
import './template';
import './topic-filters';
import './year-award-filters';

$(document).ready(() => {
    $('[data-form-type="collection"]').CollectionForm();
    $('.app-notifications').notifications();
});
