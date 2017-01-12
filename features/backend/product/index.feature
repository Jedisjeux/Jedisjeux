@ui @backend @product @index
Feature: List products
  In order to manage products
  As an administrator
  I need to be able to list products

  Background:
    Given there are users:
      | first_name | email             | role       | password |
      | Chuck      | admin@example.com | ROLE_ADMIN | password |
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

  Scenario: List products
    Given there are products:
     | name |
     | Puerto Rico |
     | Les princes de Florence |
    When I am on "/admin/products/"
    Then I should see "Les princes de Florence"
    And I should see "Puerto Rico"