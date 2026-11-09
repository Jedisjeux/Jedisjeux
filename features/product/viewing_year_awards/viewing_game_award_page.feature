@viewing_year_awards
Feature: Viewing the page of a game award
    In order to discover the games a jury distinguished
    As a Visitor
    I want to view the page of a game award

    Background:
        Given there is a product "Puerto Rico"
        And there is a game award "Spiel des Jahres"
        And this game award has been celebrated in "2018"
        And this game award has also been celebrated in "2017"
        And there is a game award "As d'or"
        And this game award has been celebrated in "2018"

    @ui
    Scenario: Viewing the year awards of a game award
        When I check the page of the game award "Spiel des Jahres"
        Then I should see "Spiel des Jahres 2018" in this list
        And I should see "Spiel des Jahres 2017" in this list
        But I should not see "As d'or 2018" in this list
