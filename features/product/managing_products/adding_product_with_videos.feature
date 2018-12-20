@managing_products
Feature: Adding a new product with videos
    In order to extend products videos database
    As an Administrator
    I want to add a new product with videos to the website

    Background:
        Given I am a logged in administrator

    @ui @javascript
    Scenario: Adding a new product with videos
        And I want to create a new product
        And I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I add a new video "https://www.youtube-nocookie.com/embed/oyefFfCHIGs?rel=0" titled "[JdjMovies] Puerto Rico"
        When I add it
        Then I should be notified that it has been successfully created
        And the product "Puerto Rico" should appear in the website
