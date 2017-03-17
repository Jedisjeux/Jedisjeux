@ui @backend @staticContent @delete
Feature: Remove job offer
  In order to manage static contents
  As an admin
  I need to be able to remove static contents

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And init doctrine phpcr repository
    And there are static contents:
      | title                                    |
      | Nouveau logements quartier Mont-Gaillard |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Remove a static content
    Given I am on "/admin/static-contents/"
    When I press "Supprimer"
    Then I should see "a bien été supprimé"