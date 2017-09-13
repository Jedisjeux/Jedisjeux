@ui @frontend @product @index
Feature: View list of products
    In order to navigate on products list
    As a visitor
    I need to be able to view all the products

    Background:
        Given there are root taxons:
            | code       | name       |
            | mechanisms | Mécanismes |
        And there are taxons:
            | code        | name     | parent     |
            | mechanism-1 | Enchères | mecanismes |
            | mechanism-2 | Majorité | mecanismes |
        And there are products:
            | name      |
            | Palazzo   |
            | Louis XIV |
        And product "Palazzo" has following taxons:
            | slug                |
            | mecanismes/encheres |
        And product "Louis XIV" has following taxons:
            | slug                |
            | mecanismes/majorite |

    Scenario: View list of products
        When I am on "/jeux-de-societe/"
        Then I should see "Palazzo"
        And I should see "Louis XIV"

    Scenario: View list of products under a taxon
        Given I am on "/jeux-de-societe/"
        When I follow "Enchères"
        Then I should see "Palazzo"
        But I should not see "Louis XIV"

    Scenario: Sorting products
        Given I am on "/jeux-de-societe/"
        When I follow "Date de création"
        Then I should see "Palazzo"
        When I follow "Note"
        Then I should see "Palazzo"
