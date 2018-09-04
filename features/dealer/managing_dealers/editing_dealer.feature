@managing_dealers
Feature: Editing a dealer
    In order to change information about a dealer
    As an Administrator
    I want to be able to edit the dealer

    Background:
        Given there is dealer "Philibert"
        And I am a logged in administrator

    @ui
    Scenario: Renaming an existing dealer
        Given I want to edit "Philibert" dealer
        When I change its name as "Esprit Jeu"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this dealer with name "Esprit Jeu" should appear in the website
