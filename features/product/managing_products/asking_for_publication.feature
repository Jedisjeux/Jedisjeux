@managing_products
Feature: Asking for publication
    In order to publish products
    As a reviewer
    I need to be able to ask for a publication

    Background:l
        Given there is a publisher "yoda@example.com"
        And there is a publisher "god@example.com"
        And there is a product "Puerto Rico" with "pending_review" status
        And I am a logged in reviewer

    @ui
    Scenario: Asking for a product publication as a reviewer
        Given I want to edit "Puerto Rico" product
        When I ask for a publication
        Then I should be notified that it has been successfully edited
        And this product with name "Puerto Rico" should have "pending publication" status
        And customer "yoda@example.com" should have received a notification for product "Puerto Rico"
        And customer "god@example.com" should also have received a notification for product "Puerto Rico"
