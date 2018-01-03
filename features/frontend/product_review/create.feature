@ui @frontend @productReview @update
Feature: Create product reviews
    In order to manage product reviews
    As a user
    I need to be able to create product reviews

    Background:
        Given there are users:
            | email             | password |
            | kevin@example.com | password |
        And there are products:
            | name        |
            | Puerto Rico |
        And I am logged in as user "kevin@example.com" with password "password"

    @javascript @todo
    Scenario: Create a product review
        Given I am on "/jeu-de-societe/puerto-rico"
        And I follow "Votre avis"
        And I fill in the following:
            | Titre | Superbe jeu |
        And I fill in wysiwyg field "sylius_product_review_comment" with "Here is my awesome review."
        When I press "Créer"
        Then I should see "a bien été créé"
