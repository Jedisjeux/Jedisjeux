@ui @frontend @staticContent @show
Feature: As a visitor
  I need to be able to see a static content page

  Background:
    Given init doctrine phpcr repository
    And there are static contents:
      | name             | title            | body                                                   |
      | mentions-legales | Mentions légales | <h2>Fiche d'identitité de l'association Jedisjeux</h2> |

  @todo
  Scenario: See as static content page
    When I am on "/pages/mentions-legales"
    Then I should see "Fiche d'identitité de l'association Jedisjeux"G