@ui @frontend @topic @index
Feature: View list of topics
    In order to use forum
    As a visitor
    I need to be able to view all the topics

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

    Scenario: Does not allow indexing private topics
        Given there are taxons:
            | code     | name                | parent | public |
            | tatooine | Tatooine la Cantina | forum  | 0      |
        And there are topics:
            | title                        | main_taxon                | author            |
            | Topic in Tatooine la Cantina | forum/tatooine-la-cantina | kevin@example.com |
        When I am on "/topics/"
        Then I should not see "Topic in Tatooine la Cantina"

    Scenario: Sorting topics
        Given I am on "/topics/"
        When I follow "Date de mise à jour"
        Then the response status code should be 200
        When I follow "Date de création"
        Then the response status code should be 200
        When I follow "Les plus commentés"
        Then the response status code should be 200
