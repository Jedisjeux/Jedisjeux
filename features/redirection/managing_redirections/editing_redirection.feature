@managing_redirections
Feature: Editing a redirection
    In order to change information about a redirection
    As an Administrator
    I want to be able to edit the redirection

    Background:
        Given there is redirection "/url-source-1"
        And I am a logged in administrator

    @ui
    Scenario: Changing source of an existing redirection
        Given I want to edit "/url-source-1" redirection
        When I change its source as "/url-source-2"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And this redirection with source "/url-source-2" should appear in the website
