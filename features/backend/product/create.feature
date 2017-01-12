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
    And I follow "Nouveau jeu"
    And I fill in the following:
      | Nom | Les Princes de Florence |
    When I press "Créer"
    Then I should see "a bien été créé"