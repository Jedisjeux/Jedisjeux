@viewing_product_videos
Feature: Viewing product videos on product's details page
    In order to see videos of a product
    As a Visitor
    I want to be able to view a video of a single product

    Background:
        Given there is a product "Puerto Rico"
        And this product has a video titled "Great board game"
        And this product has also a video titled "Best board game ever"
        And this product has also a video titled "[CDLB] Puerto Rico"
        And I am a logged in customer

    @ui
    Scenario: Viewing videos on product's details page
        When I check this product's details
        Then I should see 3 product videos
        And I should see videos titled "Great board game", "Best board game ever" and "[CDLB] Puerto Rico"
