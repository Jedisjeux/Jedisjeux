@backend @people @create
Feature: Creates people
  In order to manage people
  As an admin
  I need to be able to create people

  Background:
    Given there are users:
      | email             | role       | password |
      | admin@example.com | ROLE_ADMIN | password |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Create a person
    Given I am on "/admin/people/"
    And I follow "Nouvelle personne"
    And I fill in the following:
      | Prénom | Reiner |
      | Nom    | Knizia |
    When I press "Créer"
    Then I should see "a bien été créé"