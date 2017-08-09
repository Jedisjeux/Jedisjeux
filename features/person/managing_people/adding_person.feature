@managing_people
Feature: Adding a new person
  In order to extend people database
  As an Administrator
  I want to add a new person to the website

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: Adding a new person with firstName and LastName
    Given I want to create a new person
    When I specify his first name as "Reiner"
    And I specify his last name as "Knizia"
    When I add it
    Then I should be notified that it has been successfully created
    And the person "Reiner Knizia" should appear in the website