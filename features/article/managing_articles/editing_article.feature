@managing_articles
Feature: Editing a article
    In order to change information about a article
    As an Administrator
    I want to be able to edit the article

    Background:
        Given there is customer with email "author@example.com"
        And there is article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is article "Critique de Mafiozoo" written by "author@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Renaming an existing article
        Given I want to edit "Le Jedisjeux nouveau est arrivé" article
        When I change its title as "Star Wars"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this article with title "Star Wars" should appear in the website
