@ui @backend @product @create
Feature: Creates products
  In order to manage products
  As an staff user
  I need to be able to create products

  Background:
    Given there are users:
      | email                | role          | password |
      | admin@example.com    | ROLE_ADMIN    | password |
      | redactor@example.com | ROLE_REDACTOR | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are taxons:
      | parent | name            |
      | themes | Science-fiction |
      | themes | Fantastique     |

  Scenario: Create a product from BoardGameGeek as an administrator
    Given I am logged in as user "admin@example.com" with password "password"
    And I am on "/admin/products/bgg/new?bggPath=https://boardgamegeek.com/boardgame/3076/puerto-rico"
    And I fill in the following:
      | Slug | puerto-rico |
    When I press "Créer"
    Then I should see "a bien été créé"
    And "Puerto Rico" product should exist

  Scenario: Create a product from BoardGameGeek as a redactor
    Given I am logged in as user "redactor@example.com" with password "password"
    And I am on "/admin/products/bgg/new?bggPath=https://boardgamegeek.com/boardgame/3076/puerto-rico"
    And I fill in the following:
      | Slug | puerto-rico |
    When I press "Créer"
    Then I should see "a bien été créé"
    And "Puerto Rico" product should exist