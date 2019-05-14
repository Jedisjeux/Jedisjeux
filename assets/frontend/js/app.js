import 'bootstrap/dist/js/bootstrap.bundle';
import $ from 'jquery';

import 'sylius/ui/app';
import 'sylius/ui/sylius-auto-complete';

import './app-notifications';
import './article-filters';
import './page-views';
import './person-filters';
import './product-list';
import './rating';
import './template';
import './topic-filters';
import './year-award-filters';

$(document).ready(() => {
    $('.sylius-autocomplete').autoComplete();

    // $('[data-form-type="collection"]').CollectionForm();
    $('.app-notifications').notifications();
});
