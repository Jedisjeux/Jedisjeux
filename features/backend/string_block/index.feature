@ui @backend @stringBlock @index
Feature: View list of string blocks
  In order to manage string blocks
  As an administrator
  I need to be able to view all the string blocks

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And init doctrine phpcr repository
    And there are following string blocks:
      | name      |
      | block-one |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of string blocks
    When I am on "/admin/string-blocks/"
    Then I should see "block-one"