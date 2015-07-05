@comptaCustomer
Feature: Les clients

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des clients
    When I am on "/compta/client"
    Then I should see "Liste des clients"

  Scenario: Créer un client
    Given I am on "/compta/client/"
    And I follow "Créer un client"
    And I should be on "/compta/client/new"
    When I fill in the following:
      | Société    | Philibert                   |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complément d'adresse | bât C002                    |
      | Code postal | 35740                       |
      | Ville      | Pacé                        |
    And I press "Créer"
    Then I should be on "/compta/client/"
    And I should see "Philibert"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un client
    Given I am on "/compta/client/"
    And I follow "Créer un client"
    And I fill in the following:
      | Société    | Philibert                   |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complément d'adresse | bât C002                    |
      | Code postal | 35740                       |
      | Ville      | Pacé                        |
    And I press "Créer"
    And I follow "Modifier"
    When I fill in the following:
      | Société | Ludibay |
    And I press "Modifier"
    Then I should be on "/compta/client/"
    And I should see "Ludibay"
    And I should not see "Philibert"

  Scenario: Supprimer un client
    Given I am on "/compta/client"
    And I follow "Créer un client"
    And I fill in the following:
      | Société    | Philibert                   |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complément d'adresse | bât C002                    |
      | Code postal | 35740                       |
      | Ville      | Pacé
    And I press "Créer"
    When I press "Supprimer"
    Then I should be on "/compta/client/"
    And I should not see "Philibert"