@viewing_counters_on_homepage
Feature: Viewing user count
    In order to be informed about database size
    As a Visitor
    I want to be able to view user count

    Background:
        Given there is a user "kevin@example.com"
        And there is also a user "loic@example.com"
        And there is also a user "pierre@example.com"

    @ui
    Scenario: Viewing user count
        When I check counters
        Then I should see 3 as user count
