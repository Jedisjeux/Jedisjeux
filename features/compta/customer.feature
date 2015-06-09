@comptaCustomer
Feature: Les clients

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des clients
    When I am on "/compta/customer"
    Then I should see "Liste des clients"

  Scenario: Créer un client
    Given I am on "/compta/customer/"
    And I follow "Créer un client"
    And I should be on "/compta/customer/new/"
    When I fill in the following:
      | Societe    | Philibert                   |
      | Nom        | Loïc Fremont                |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complement | bât C002                    |
      | Codepostal | 35740                       |
      | Ville      | Pacé                        |
    And I press "Créer"
    Then I should be on "/compta/customer/"
    And I should see "Philibert"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un client
    Given I am on "/compta/customer/"
    And I follow "Créer un nouveau client"
    And I fill in the following:
      | Societe    | Philibert                   |
      | Nom        | Loïc Fremont                |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complement | bât C002                    |
      | Codepostal | 35740                       |
      | Ville      | Pacé                        |
    And I press "Créer"
    And I follow "Modifier"
    When I fill in the following:
      | Societe | Ludibay |
    And I press "Enregistrer"
    Then I should be on "/compta/customer/"
    And I should see "Ludibay"
    And I should not see "Philibert"

  Scenario: Supprimer un client
    Given I am on "/compta/customer"
    And I follow "Créer un nouveau client"
    And I fill in the following:
      | Societe    | Philibert                   |
      | Nom        | Loïc Fremont                |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complement | bât C002                    |
      | Codepostal | 35740                       |
      | Ville      | Pacé
    And I press "Créer"
    When I press "Supprimer"
    Then I should be on "/compta/customer/"
    And I should not see "Philibert"