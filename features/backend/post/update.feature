@ui @backend @post @update
Feature: Edit posts
  In order to manage posts
  As an admin
  I need to be able to update posts

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
    And there are posts:
      | topic                          | author            |
      | Retour de Cannes jour par jour | kevin@example.com |
    And I am logged in as user "admin@example.com" with password "password"

  Scenario: Update a post
    Given I am on "/admin/posts/"
    And I follow "Modifier"
    When I press "Enregistrer les modifications"
    Then I should see "a bien été mis à jour"