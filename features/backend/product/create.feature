@ui @backend @product @create
Feature: Creates products
  In order to manage products
  As an administrator
  I need to be able to create products

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are taxons:
      | parent | name            |
      | themes | Science-fiction |
      | themes | Fantastique     |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a product
    Given I am on "/admin/products/"
    And I click on "Créer" dropdown
    And follow "Produit standard"
    And I fill in the following:
      | Nom | Les Princes de Florence |
    When I press "Créer"
    Then I should see "a bien été créé"
    And "Les Princes de Florence" product should exist

  Scenario: Create a product from BoardGameGeek
    Given I am on "/admin/products/bgg/new?bggPath=https://boardgamegeek.com/boardgame/3076/puerto-rico"
    When I press "Créer"
    Then I should see "a bien été créé"
    And "Puerto Rico" product should exist