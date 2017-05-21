@ui @backend @productVariant @create
Feature: Creates product variants
  In order to manage product variants
  As an admin
  I need to be able to create product variants

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code            | name         |
      | themes          | Thèmes       |
      | mechanisms      | Mécanismes   |
      | target-audience | Public cible |
    And there are products:
      | name                    |
      | Les princes de Florence |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a product variant
    Given I am on "/admin/products/"
    And I click on "Gérer les variantes" dropdown
    And I follow "Ajouter"
    And I fill in the following:
      | Nom | Die fürsten von Florenz |
    When I press "Créer"
    Then I should see "a bien été créé"

  @todo
  Scenario: Cannot create empty product variant
    Given I am on "/admin/products/"
    And I click on "Gérer les variantes" dropdown
    And I follow "Créer"
    When I press "Créer"
    Then I should see "Cette valeur ne doit pas être vide."
