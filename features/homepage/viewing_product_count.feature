@viewing_counters_on_homepage
Feature: Viewing product count
    In order to be informed about database size
    As a Visitor
    I want to be able to view product count

    Background:
        Given there is a product "Shogun"
        And there is also a product "Puerto Rico"
        And there is also a product "Modern Art"

    @ui
    Scenario: Viewing product count
        When I check counters
        Then I should see 3 as product count
