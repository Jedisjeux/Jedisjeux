@managing_people
Feature: Adding a new person
  In order to extend my merchandise
  As an Administrator
  I want to add a new person to the shop

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: Adding a new person with firstName and LastName
    Given I want to create a new person