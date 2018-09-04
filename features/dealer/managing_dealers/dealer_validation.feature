@managing_dealers
Feature: Dealers validation
    In order to avoid making mistakes when managing dealers
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Trying to add a new dealer without name
        Given I want to create a new dealer
        When I do not specify its name
        And I try to add it
        Then I should be notified that the name is required
        And this dealer should not be added
