@viewing_articles
Feature: Disallow viewing unpublished product details
  In order to prevent me for viewing unpublished articles detailed information
  As a Visitor
  I am not be able to view a article which is not published

  Background:
    Given there are default taxonomies for articles
    And there is customer with email "kevin@example.com"
  
  @ui
  Scenario: Trying to view details of a article with new status
    When there is article "Awesome article" written by "kevin@example.com" with "new" status
    Then I should not be able to see this article's details

  @ui
  Scenario: Trying to view details of a article with pending review status
    When there is article "Awesome article" written by "kevin@example.com" with "pending review" status
    Then I should not be able to see this article's details

  @ui
  Scenario: Trying to view details of a article with pending publication status
    When there is article "Awesome article" written by "kevin@example.com" with "pending publication" status
    Then I should not be able to see this article's details