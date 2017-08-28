@managing_dealers
Feature: Browsing dealers
    In order to see all dealers in the website
    As an Administrator
    I want to browse dealers

    Background:
        Given there is dealer "Philibert"
        And there is dealer "Esprit Jeu"
        And there is dealer "Ludifolie"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing dealers in website
        When I want to browse dealers
        Then there should be 3 dealers in the list
        And I should see the dealer "Philibert" in the list
