@ui @frontend @topic @show
Feature: View topic
    In order to manage topics
    As a visitor
    I need to be able to view a topic

    Background:
        Given there are users:
            | email             |
            | kevin@example.com |
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

    Scenario: View a topic
        Given I am on "/topics/"
        When I follow "Retour de Cannes jour par jour"
        Then I should see "Retour de Cannes jour par jour"
        And I should see "Répondre au sujet"
