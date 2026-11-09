@managing_year_awards
Feature: Editing a year award
    In order to fix the year of an award
    As an Administrator
    I want to be able to edit year awards

    Background:
        Given there is a game award "Spiel des Jahres"
        And there is a product "Puerto Rico"
        And game award "Spiel des Jahres" has been attributed to this product in "2016"
        And there is a product "Modern Art"
        And game award "Spiel des Jahres" has been attributed to this product in "2017"
        And I am a logged in administrator

    @ui
    Scenario: Changing the year of a year award
        Given I want to edit the year award of "2017"
        When I change its year to "2018"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And I should see the year award "Spiel des Jahres 2018" in the list
        But I should not see the year award "Spiel des Jahres 2017" in the list
