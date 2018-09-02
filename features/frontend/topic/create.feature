@ui @frontend @topic @create
Feature: Topic creation
    In order to use forum
    As a user
    I need to be able to create new topics

    Background:
        Given there are following users:
            | email             | password | role      |
            | kevin@example.com | password | ROLE_USER |
        Given there are root taxons:
            | code  | name  |
            | forum | Forum |
        And there are taxons:
            | name                | parent |
            | La taverne des jeux | forum  |
        And I am logged in as user "kevin@example.com" with password "password"

    @javascript
    Scenario: Create new topic for taxon
        Given I am on "/topics/"
        And I follow "La taverne des jeux"
        And I follow "Nouveau sujet"
        And I fill in the following:
            | Titre | Zoo Topic |
        And I fill in wysiwyg field "app_topic_mainPost_body" with "Here is my awesome topic message."
        When I press "Créer"
        Then I should see "a bien été créé"
        And "Zoo Topic" topic should be categorized under "forum/la-taverne-des-jeux" taxon
