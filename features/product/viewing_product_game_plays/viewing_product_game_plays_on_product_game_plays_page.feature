@viewing_product_game_plays
Feature: Viewing product game plays on product's game plays page
    In order to read other customer's game plays on a product
    As a Customer
    I want to read all product game plays on product's game plays page

    Background:
        Given there is a product "Necronomicon"
        And this product has a game play added by customer "h.p.lovecraft@arkham.com" with 2 comments
        And this product has also a game play added by customer "robert.e.howard@conan.com" with 3 comments
        And this product has also a game play added by customer "jrr.tolkien@middle-earth.com" with 2 comments
        And I am a logged in customer

    @ui
    Scenario: Viewing all game plays on product's game plays page
        When I check this product's game plays
        Then I should see 3 game plays in the list

    @ui
    Scenario: Viewing no game play message if there are no game plays
        Given there is a product "Lux perpetua"
        When I check this product's game plays
        Then I should be notified that there are no game plays
