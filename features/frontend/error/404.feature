@ui @frontend @error @404
Feature: As a visitor
  I need to be able to see a 404 when page does not exist

  Scenario: See a 404
    When I am on "/qsdfmoijg"
    Then I should see "Page introuvable"