@ui @frontend @productReview @update
Feature: Edit product reviews
  In order to manage product reviews
  As a user
  I need to be able to update my product reviews

  Background:
    Given there are users:
      | email             | password |
      | kevin@example.com | password |
    And there are products:
      | name        |
      | Puerto Rico |
    And there are product reviews:
      | product     | author            | title     |
      | Puerto Rico | kevin@example.com | Super jeu |
    And I am logged in as user "kevin@example.com" with password "password"

  @javascript
  Scenario: Update a product review
    Given I am on "/jeu-de-societe/puerto-rico"
    And I follow "Votre avis"
    When I press "Mettre à jour"
    Then I should see "a bien été mis à jour"