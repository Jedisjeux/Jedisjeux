@ui @backend @article @create
Feature: Creates articles
  In order to manage articles
  As a staff user
  I need to be able to create articles

  Background:
    Given there are users:
      | email                | role          | password |
      | admin@example.com    | ROLE_ADMIN    | password |
      | redactor@example.com | ROLE_REDACTOR | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create an article as an administrator
    Given I am logged in as user "admin@example.com" with password "password"
    And I am on "/admin/articles/"
    And I follow "Créer"
    And I fill in the following:
      | Titre | King of New York : Power Up! |
    When I press "Créer"
    Then I should see "a bien été créé"

  Scenario: Create an article as a redactor
    Given I am logged in as user "redactor@example.com" with password "password"
    And I am on "/admin/articles/"
    And I follow "Créer"
    And I fill in the following:
      | Titre | King of New York : Power Up! |
    When I press "Créer"
    Then I should see "a bien été créé"

  Scenario: Cannot create empty article
    Given I am on "/admin/articles/"
    And I follow "Créer"
    When I press "Créer"
    Then I should see "Cette valeur ne doit pas être vide."
