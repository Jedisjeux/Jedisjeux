@managing_dealers
Feature: Deleting a dealer
    In order to get rid of deprecated dealers
    As an Administrator
    I want to be able to delete dealers

    Background:
        Given there is dealer "Philibert"
        And there is dealer "Esprit Jeu"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a dealer
        Given I want to browse dealers
        When I delete dealer with name "Philibert"
        Then I should be notified that it has been successfully deleted
        And there should not be "Philibert" dealer anymore
