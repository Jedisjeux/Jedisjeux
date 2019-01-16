@viewing_counters_on_homepage
Feature: Viewing article count
    In order to be informed about database size
    As a Visitor
    I want to be able to view article count

    Background:
        Given there are default taxonomies for articles
        And there is an article "Le Jedisjeux nouveau est arrivé" written by "author@example.com"
        And there is an article "Critique de Vikings Gone Wild" written by "author@example.com"
        And there is an article "Critique de Mafiozoo" written by "author@example.com"

    @ui
    Scenario: Viewing article count
        When I check counters
        Then I should see 3 as article count
