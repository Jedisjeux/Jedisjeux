@managing_year_awards
Feature: Browsing year awards
    In order to see all year awards in the website
    As an Administrator
    I want to browse year awards

    Background:
        Given there is a game award "Spiel des Jahres"
        And there is a product "Puerto Rico"
        And game award "Spiel des Jahres" has been attributed to this product in "2016"
        And there is a product "Modern Art"
        And game award "Spiel des Jahres" has been attributed to this product in "2017"
        And there is a product "Euphrat & Tigris"
        And game award "Spiel des Jahres" has been attributed to this product in "2018"
        And I am a logged in administrator

    @ui
    Scenario: Browsing year awards in website
        When I want to browse year awards
        Then there should be 3 year awards in the list
        And I should see the year award "Spiel des Jahres 2018" in the list
