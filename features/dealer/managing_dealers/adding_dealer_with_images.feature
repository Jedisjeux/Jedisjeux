@managing_dealers
Feature: Adding a new dealer with images
    In order to extend dealers database
    As an Administrator
    I want to add a new dealer with images to the website

    Background:
        Given I am a logged in administrator

    @ui @javascript
    Scenario: Adding a new dealer with an image
        Given I want to create a new dealer
        When I specify his code as "philibert"
        And I specify his name as "Philibert"
        And I attach the "dealers/philibert.jpg" image
        And I add it
        Then I should be notified that it has been successfully created
        And the dealer "Philibert" should have an image
