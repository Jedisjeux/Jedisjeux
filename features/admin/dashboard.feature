@admin_dashboard
Feature: Statistics dashboard
    In order to have an overview of activities
    As an Administrator
    I want to see overall statistics on my admin dashboard

    @todo
    Scenario: Seeing recent customers
    @ui
    Scenario: Seeing the dashboard as an administrator
        When I am a logged in administrator
        Then I should be able to see the dashboard

    @ui
    Scenario: Seeing the dashboard as a product manager
        When I am a logged in product manager
        Then I should be able to see the dashboard

    @ui
    Scenario: Seeing the dashboard as an article manager
        When I am a logged in article manager
        Then I should be able to see the dashboard

    @ui
    Scenario: Seeing the dashboard as a staff user
        When I am a logged in staff user
        Then I should be able to see the dashboard
