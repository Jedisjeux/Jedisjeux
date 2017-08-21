@managing_products
Feature: Adding a new product
  In order to extend products database
  As an Administrator
  I want to add a new product to the website

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: Adding a new product with name and slug
    Given I want to create a new product
    And I specify his name as "Puerto Rico"
    And I specify his slug as "puerto-rico"
    When I add it
    Then I should be notified that it has been successfully created
    And the product "Puerto Rico" should appear in the website