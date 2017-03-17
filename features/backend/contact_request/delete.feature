@ui @backend @contactRequest @delete
Feature: Remove contact requests
  In order to manage contact requests
  As an admin
  I need to be able to remove contact requests

  Background:
    Given there are users:
      | email             | role       | password |
      | kevin@example.com | ROLE_USER  | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are contact requests:
      | email             |
      | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a contact request
    Given I am on "/admin/contact-requests/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"

  @javascript
  Scenario: Remove a contact request with modal
    Given I am on "/admin/contact-requests/"
    When I press "Supprimer"
    And I wait until modal is visible
    And I press confirm button
    Then I should see "a bien été supprimé"