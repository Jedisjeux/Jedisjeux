@comptaSubscription
Feature: The Subscription

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des abonnements
    When I am on "/compta/abonnement"
    Then I should see "Liste des abonnements"