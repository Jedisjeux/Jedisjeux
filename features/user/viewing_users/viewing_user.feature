@viewing_users
    Feature: Viewing user
        In order to learn more about a user
        As a visitor
        I want to view user's details

    Background:
        Given there is a user with username "Smil"

    @ui
    Scenario: Viewing user's details
        When I check his details
        Then I should see the username "Smil"
