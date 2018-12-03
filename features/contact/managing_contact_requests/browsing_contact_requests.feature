@managing_contact_requests
Feature: Browsing contact requests
    In order to see read contact requests in the website
    As an Administrator
    I want to browse contact requests

    Background:
        Given there is a contact request from "darth.vader@example.com"
        And there is also a contact request from "yoda@example.com"
        And I am a logged in administrator

    @ui
    Scenario: Browsing contact requests in website
        When I want to browse contact requests
        Then there should be 2 contact requests in the list
        And I should see the contact request from "darth.vader@example.com" in the list
