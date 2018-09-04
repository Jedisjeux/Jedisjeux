@viewing_product_game_plays
Feature: Viewing product game plays on product's details page
    In order to read other customer's game plays
    As a Customer
    I want to read product game plays on product's details page

    Background:
        Given there is a product "Necronomicon"
        And this product has a game play added by customer "h.p.lovecraft@arkham.com", created 3 days ago
        And this product has also a game play added by customer "robert.e.howard@conan.com"
        And this product has also a game play added by customer "jrr.tolkien@middle-earth.com"
        And this product has also a game play added by customer "sir.terry@pratchett.com"
        And I am a logged in customer

    @ui @todo
    Scenario: Viewing last 3 game plays on product's details page
        When I check this product's details
        Then I should see 3 game plays
        And I should see game plays on products "Classic", "Too gloomy" and "Scary and dark"
        But I should not see game play on product "Good book"
