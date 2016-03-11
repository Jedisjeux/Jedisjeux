@api @taxonomies @index
Feature: View list of taxonomies
  In order to manage taxonomies
  As a remote
  I need to be able to view all the taxonomies

  Background:
    Given there are following taxonomies:
      | name    |
      | classes |

  Scenario: View list of taxonomies
    When I am on "/"