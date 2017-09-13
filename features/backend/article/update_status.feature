@ui @backend @article @update_status
Feature: Update article status
    In order to manage articles
    As an admin
    I need to be able to update article status

    Background:
        Given there are users:
            | email                  | role            | password |
            | admin@example.com      | ROLE_ADMIN      | password |
            | redactor@example.com   | ROLE_REDACTOR   | password |
            | translator@example.com | ROLE_TRANSLATOR | password |
            | reviewer@example.com   | ROLE_REVIEWER   | password |
            | publisher@example.com  | ROLE_PUBLISHER  | password |
        And there are root taxons:
            | code     | name     |
            | articles | Articles |
        And there are taxons:
            | code    | name       | parent   |
            | news    | Actualités | articles |
            | reviews | Critiques  | articles |

    Scenario: Ask for review as a redactor
        Given I am logged in as user "redactor@example.com" with password "password"
        And there are articles:
            | taxon               | title                        | status | author               |
            | articles/actualites | King of New York : Power Up! | new    | redactor@example.com |
        And I am on "/admin/articles/"
        And I follow "Modifier"
        When I press "Demander une relecture"
        Then I should see "a bien été mis à jour"
        And "King of New York : Power Up!" article should have "pending_review" status
        And there is a notification to "reviewer@example.com" for "King of New York : Power Up!" article

    Scenario: Ask for publication as a reviewer
        Given I am logged in as user "reviewer@example.com" with password "password"
        And there are articles:
            | taxon               | title                        | status         | author               |
            | articles/actualites | King of New York : Power Up! | pending_review | redactor@example.com |
        And I am on "/admin/articles/"
        And I follow "Modifier"
        When I press "Demander la publication"
        Then I should see "a bien été mis à jour"
        And "King of New York : Power Up!" article should have "pending_publication" status
        And there is a notification to "publisher@example.com" for "King of New York : Power Up!" article

    Scenario: Publish as a publisher
        Given I am logged in as user "publisher@example.com" with password "password"
        And there are articles:
            | taxon               | title                        | status              | author               |
            | articles/actualites | King of New York : Power Up! | pending_publication | redactor@example.com |
        And I am on "/admin/articles/"
        And I follow "Modifier"
        When I press "Publier"
        Then I should see "a bien été mis à jour"
        And "King of New York : Power Up!" article should have "published" status
