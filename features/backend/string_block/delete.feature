@ui @backend @stringBlock @delete
Feature: Remove string block
  In order to manage string blocks
  As an admin
  I need to be able to remove string blocks

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And init doctrine phpcr repository
    And there are string blocks:
      | name      |
      | block-one |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a string block
    Given I am on "/admin/string-blocks/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"