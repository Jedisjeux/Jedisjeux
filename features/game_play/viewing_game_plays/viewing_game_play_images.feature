@viewing_game_plays
Feature: Viewing the images of the game plays
    In order to see how the members play
    As a Visitor
    I want to browse the images of the game plays

    Background:
        Given there is a product "Puerto Rico"
        And this product has one game play from customer "kevin@example.com"
        And this game play has an image described as "The final scoring"
        And this game play has also an image described as "The set-up"

    @ui
    Scenario: Browsing the images of the game plays
        When I browse the game play images
        Then there should be 2 items in this list
        And I should see "The final scoring" in this list
