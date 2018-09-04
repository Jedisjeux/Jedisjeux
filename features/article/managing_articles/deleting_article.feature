@managing_articles
Feature: Deleting a article
    In order to get rid of deprecated articles
    As an Administrator
    I want to be able to delete articles

    Background:
        Given there is a customer with email "author@example.com"
        And there are default taxonomies for articles
        And there is an article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is an article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is an article "Critique de Mafiozoo" written by "author@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Deleting an article
        Given I want to browse articles
        When I delete article with title "Le Jedisjeux nouveau est arrivé"
        Then I should be notified that it has been successfully deleted
        And there should not be "Philibert" article anymore
