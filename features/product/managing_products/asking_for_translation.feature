@managing_products
Feature: Asking for translation
    In order to translate products
    As a redactor
    I need to be able to ask for a translation

    Background:
        Given there is a translator "obiwan@example.com"
        And there is a translator "luke@example.com"
        And I am a logged in redactor
        And there is a product "Puerto Rico" with "new" status

    @ui
    Scenario: Asking for a product translation as a redactor
        Given I want to edit "Puerto Rico" product
        When I ask for a translation
        Then I should be notified that it has been successfully edited
        And this product with name "Puerto Rico" should have "pending translation" status
        And there is a notification sent to "obiwan@example.com" for product "Puerto Rico"
        And there is also a notification sent to "luke@example.com" for product "Puerto Rico"
