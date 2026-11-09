@viewing_articles
Feature: Viewing the games presented in the blocks of an article
    In order to reach the games an article talks about
    As a visitor
    I want to see the game linked to each block of the article

    Background:
        Given there are default taxonomies for articles
        And there is a product "Puerto Rico"
        And there is also a product "Agricola"
        And there is an article "Spiel des Jahres 2026 : les résultats" written by "kevin@example.com"
        And this article has a block titled "Le lauréat" presenting product "Puerto Rico"
        And this article has a block titled "Le jeu expert" presenting product "Agricola"

    @ui
    Scenario: Viewing the game presented in each block
        When I check this article's details
        Then I should see product "Puerto Rico" presented in the "Le lauréat" block
        And I should see product "Agricola" presented in the "Le jeu expert" block
