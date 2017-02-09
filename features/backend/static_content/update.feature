@ui @backend @staticContent @update
Feature: Edit static contents
  In order to manage static contents
  As an administrator
  I need to be able to update static contents

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And init doctrine phpcr repository
    And there are following static contents:
      | title                                    |
      | Nouveau logements quartier Mont-Gaillard |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a static content
    Given I am on "/admin/static-contents/"
    And I follow "Modifier"
    And I fill in the following:
      | Nom | Zone X |
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"