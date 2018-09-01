@adding_topics
Feature: Topics validation
    In order to avoid making mistakes when adding topics
    As a Visitor
    I want to be prevented from adding it without specifying required fields

    Background:
        Given there are default taxonomies for topics
        And I am logged in as a customer

    @ui
    Scenario: Trying to add a new topic without comment
        Given I want to add topic
        When I specify its title as "Awesome topic"
        And I do not leave any comment
        And I try to add it
        Then I should be notified that the comment is required
        And this topic should not be added
