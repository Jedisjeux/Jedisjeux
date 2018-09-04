@managing_articles
Feature: Browsing articles
    In order to see all articles in the website
    As an Administrator or a Redactor
    I want to browse articles

    Background:
        Given there is a customer with email "author@example.com"
        And there are default taxonomies for articles
        And there is an article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is an article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is an article "Critique de Mafiozoo" written by "author@example.com"

    @ui
    Scenario: Browsing articles in website as administrator
        Given I am logged in as an administrator
        When I want to browse articles
        Then there should be 3 articles in the list
        And I should see the article "Le Jedisjeux nouveau est arrivé" in the list

    @ui
    Scenario: Browsing articles in website as a redactor
        Given I am logged in as a redactor
        When I want to browse articles
        Then there should be 3 articles in the list
        And I should see the article "Le Jedisjeux nouveau est arrivé" in the list

    @ui
    Scenario: Browsing articles in website as a staff user
        Given I am logged in as a staff user
        When I want to browse articles
        Then there should be 3 articles in the list
        And I should see the article "Le Jedisjeux nouveau est arrivé" in the list

    @ui
    Scenario: Browsing articles in website as an article manager
        Given I am logged in as an article manager
        When I want to browse articles
        Then there should be 3 articles in the list
        And I should see the article "Le Jedisjeux nouveau est arrivé" in the list

    @ui
    Scenario: Trying to browse articles in website as a product manager
        When I am logged in as a product manager
        Then I should not be able to browse articles
