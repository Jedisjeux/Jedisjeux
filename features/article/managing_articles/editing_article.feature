@managing_articles
Feature: Editing a article
    In order to change information about a article
    As an Administrator
    I want to be able to edit the article

    Background:
        Given there are default taxonomies for articles
        And there is an article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is an article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is an article "Critique de Mafiozoo" written by "author@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing article
        Given I want to edit "Le Jedisjeux nouveau est arrivé" article
        When I change its title as "Star Wars"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this article with title "Star Wars" should appear in the website
