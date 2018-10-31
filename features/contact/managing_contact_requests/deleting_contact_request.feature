@managing_contact_requests
Feature: Deleting a contact request
    In order to get rid of deprecated contact requests
    As an Administrator
    I want to be able to delete contact requests

    Background:
        Given there is a contact request from "darth.vader@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a contact request
        Given I want to browse contact requests
        When I delete contact request with email "darth.vader@example.com"
        Then I should be notified that it has been successfully deleted
        And there should not be contact request from "darth.vader@example.com" anymore
