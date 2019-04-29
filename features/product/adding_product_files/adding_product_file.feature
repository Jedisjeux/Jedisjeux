@adding_product_files
Feature: Adding product file as a customer
    In order to propose add-ons of a game
    As a Customer
    I want to be able to add a product file

    Background:
        Given there is a product "Puerto Rico"
        And there is a reviewer "reviewer@example.com"
        And I am a logged in customer

    @ui
    Scenario: Adding a product file as a customer
        Given I want to add a file to this product
        When I attach the "files/paf_rules.pdf" file
        And I specify its title as "French Rules"
        And I add it
        Then I should be notified that my file is waiting for the acceptation
        And customer "reviewer@example.com" should have received a notification
