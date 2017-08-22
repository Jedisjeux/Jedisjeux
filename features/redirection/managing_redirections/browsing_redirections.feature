@managing_redirections
Feature: Browsing redirections
    In order to see all redirections in the website
    As an Administrator or a Redactor
    I want to browse redirections

    Background:
        Given there is redirection "http://www.example.com/url-1"
        And there is redirection "http://www.example.com/url-2"
        And there is redirection "http://www.example.com/url-3"
        And I am logged in as an administrator

    @ui
    Scenario: Browsing redirections in website
        Given I want to browse redirections
        Then there should be 3 redirections in the list
        And I should see the redirection "/url-1" in the list
