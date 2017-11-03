@requesting_contact
Feature: Requesting contact
    In order to receive help from the website's support
    As a Customer
    I want to be able to send a message to the website's support

    @ui @email
    Scenario: Requesting contact as a guest
        When I want to request contact
        And I specify the first name as "Lucy"
        And I specify the last name as "Fer"
        And I specify the email as "lucifer@morningstar.com"
        And I specify the message as "Hi! I did not receive an item!"
        And I send it
        Then I should be notified that the contact request has been submitted successfully
