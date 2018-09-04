@managing_redirections
Feature: Deleting a redirection
    In order to get rid of deprecated redirections
    As an Administrator
    I want to be able to delete redirections

    Background:
        Given there is redirection "/url-source-1"
        And there is redirection "/url-source-2"
        And I am a logged in administrator

    @ui
    Scenario: Deleting a redirection
        Given I want to browse redirections
        When I delete redirection with source "/url-source-1"
        Then I should be notified that it has been successfully deleted
        And there should not be "/url-source-1" redirection anymore
