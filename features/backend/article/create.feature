@ui @backend @article @create
Feature: Creates articles
  In order to manage articles
  As an admin
  I need to be able to create articles

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create an article
    Given I am on "/admin/articles/"
    And I follow "Créer"
    And I fill in the following:
      | Nom   | king-of-new-york-power-up    |
      | Titre | King of New York : Power Up! |
    When I press "Créer"
    Then I should see "a bien été créé"