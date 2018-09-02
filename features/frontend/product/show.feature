@ui @frontend @product @show
Feature: View a product
    In order to view information of a product
    As a visitor
    I need to be able to view a product sheet

    Background:
        Given there are root taxons:
            | code       | name       |
            | mechanisms | Mécanismes |
            | themes     | Thèmes     |
            | forums     | Forum      |
        And there are taxons:
            | code        | name       | parent     |
            | mechanism-1 | Majorité   | Mécanismes |
            | theme-1     | Historique | Thèmes     |
        And there are products:
            | name      |
            | Louis XIV |
        And product "Louis XIV" has following taxons:
            | slug                |
            | mecanismes/majorite |
            | themes/historique   |

    Scenario: View product
        Given I am on "/jeux-de-societe/"
        When I follow "Louis XIV"
        Then I should see "Louis XIV"

    @javascript @todo
    Scenario: View Articles tab
        Given I am on "/jeux-de-societe/"
        And I follow "Louis XIV"
        And I wait "5" seconds
        When I follow "Articles" on ".nav-tabs"
        And I wait "5" seconds
        Then I should see "Aucun article"

    @javascript @todo
    Scenario: View Avis tab
        Given I am on "/jeux-de-societe/"
        And I follow "Louis XIV"
        And I wait "5" seconds
        When I follow "Avis" on ".nav-tabs"
        And I wait "5" seconds
        Then I should see "Aucun avis"
