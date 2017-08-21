@managing_articles
Feature: Browsing articles
    In order to see all articles in the website
    As an Administrator
    I want to browse articles

    Background:
        Given there is customer with email "author@example.com"
        And there is article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is article "Critique de Mafiozoo" written by "author@example.com"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing articles in website
        When I want to browse articles
        Then there should be 3 articles in the list
        And I should see the article "Le Jedisjeux nouveau est arrivé" in the list
