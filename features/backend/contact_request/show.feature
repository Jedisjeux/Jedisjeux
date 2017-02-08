@ui @backend @contactRequest @show
Feature: Edit contact requests
  In order to manage contact requests
  As an admin
  I need to be able to show contact requests

  Background:
    Given there are users:
      | email             | role       | password |
      | kevin@example.com | ROLE_USER  | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are contact requests:
      | email             |
      | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a contact request
    Given I am on "/admin/contact-requests/"
    And I follow "Voir"
    Then I should see "kevin@example.com"