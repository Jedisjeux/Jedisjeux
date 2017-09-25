@viewing_products
Feature: Disallow viewing unpublished product details
    In order to prevent me for viewing unpublished products detailed information
    As a Visitor
    I am not be able to view a product which is not published

    @ui
    Scenario: Trying to view details of a product with new status
        When there is product "Puerto Rico" with "new" status
        Then I should not be able to see this product's details

    @ui
    Scenario: Trying to view details of a product with pending translation status
        When there is product "Puerto Rico" with "pending translation" status
        Then I should not be able to see this product's details

    @ui
    Scenario: Trying to view details of a product with pending review status
        When there is product "Puerto Rico" with "pending review" status
        Then I should not be able to see this product's details

    @ui
    Scenario: Trying to view details of a product with pending publication status
        When there is product "Puerto Rico" with "pending publication" status
        Then I should not be able to see this product's details