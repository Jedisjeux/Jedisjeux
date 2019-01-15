@viewing_counters_on_homepage
Feature: Viewing rating count
    In order to be informed about database size
    As a Visitor
    I want to be able to view rating count

    Background:
        Given there is a product "Shogun"
        And this product has a review titled "Excellent!" and rated 10 added by customer "kevin@example.com"
        And this product has also a review titled "Awesome!" and rated 9 added by customer "barney@example.com"
        And this product has been rated with a 5 by customer "anonymous@example.com"

    @ui
    Scenario: Viewing rating count
        When I check counters
        Then I should see 3 as rating count
