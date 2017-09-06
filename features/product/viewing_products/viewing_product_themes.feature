@viewing_products
Feature: Viewing product's themes
  In order to see product's specification
  As a visitor
  I want to be able to see product's themes

  Background:
    Given there are default taxonomies for products

  @ui
  Scenario: Viewing a detailed page with product's themes
    Given there are themes "Renaissance" and "History"
    And there is product "El grande"
    And this product has "Renaissance" theme
    And this product also has "History" theme
    When I check this product's details
    Then I should see the theme name "Renaissance"
    And I should see the theme name "History"

