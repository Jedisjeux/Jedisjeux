@api @product @show
Feature: Get product
  In order to manage products
  As a remote
  I need to be able to get product data

  Background:
    Given there are root taxons:
      | code       | name       |
      | mechanisms | Mécanismes |
      | themes     | Thèmes     |
    And there are taxons:
      | code        | name       | parent     |
      | mechanism-1 | Majorité   | Mécanismes |
      | theme-1     | Historique | Thèmes     |
    And there are products:
      | name      |
      | Louis XIV |
    And product "Louis XIV" has following taxons:
      | name       |
      | Majorité   |
      | Historique |

  Scenario: Get product data
    When I request get "Louis XIV" product
    Then the api response status code should be 200
    And the response has a "name" property equals to Louis XIV
    And the response has a "themes" property equals to [{"permalink":"themes\/historique","name":"Historique"}]
    And the response has a "mechanisms" property equals to [{"permalink":"mecanismes\/majorite","name":"Majorit\u00e9"}]