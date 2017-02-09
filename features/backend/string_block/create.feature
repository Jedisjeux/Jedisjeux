@ui @backend @stringBlock @create
Feature: Create new string block
  In order to manage string blocks
  As an administrator
  I need to be able to create new string blocks

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And init doctrine phpcr repository
    And I am logged in as user "admin@example.com" with password "password"

  @javascript
  Scenario: Create new string block
    Given I am on "/admin/string-blocks/"
    And I follow "Créer"
    And I fill in the following:
      | Nom | block-one |
    And I fill in wysiwyg field "app_string_block_body" with "<p>Lorem Ipsum Dolor</p>"
    When I press "Créer"
    Then I should see "a bien été créé"