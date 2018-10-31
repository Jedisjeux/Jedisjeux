@managing_contact_requests
Feature: Viewing a contact request
    In order to get read contact requests
    As an Administrator
    I want to be able to view a single contact request

    Background:
        Given there is a contact request from "darth.vader@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Viewing a contact request
        When I check this contact request's details
        Then I should see the contact request email "darth.vader@example.com"
