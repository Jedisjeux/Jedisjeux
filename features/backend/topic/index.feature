@ui @backend @topic @index
Feature: View list of topics
  In order to manage topics
  As an administrator
  I need to be able to view all the topics

  Background:
    Given there are users:
      | email             | role       | password |
      | kevin@example.com | ROLE_USER  | password |
      | admin@example.com | ROLE_ADMIN | password |
    And there are root taxons:
      | code  | name  |
      | forum | Forum |
    And there are taxons:
      | code | name            | parent |
      | 666  | Moi je dis jeux | forum  |
      | XYZ  | Réglons-ça      | forum  |
    And there are topics:
      | title                          | main_taxon            | author            |
      | Retour de Cannes jour par jour | forum/reglons-ca      | kevin@example.com |
      | Jeux avec handicap             | forum/moi-je-dis-jeux | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: View list of topics
    When I am on "/admin/topics/"
    Then I should see "Retour de Cannes jour par jour"
    And I should see "Jeux avec handicap"