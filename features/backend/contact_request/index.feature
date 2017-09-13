@ui @backend @contactRequest @index
Feature: View list of contact requests
    In order to manage contact requests
    As an administrator
    I need to be able to view all the contact requests

    Background:
        Given there are users:
            | email             | role       | password |
            | kevin@example.com | ROLE_USER  | password |
            | admin@example.com | ROLE_ADMIN | password |
        And there are contact requests:
            | email             |
            | kevin@example.com |
        And I am logged in as user "admin@example.com" with password "password"

    Scenario: View list of contact requests
        When I am on "/admin/contact-requests/"
        Then I should see "kevin@example.com"
