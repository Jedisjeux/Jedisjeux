@client
Feature: Les clients

  Background:
    Given there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |


  Scenario: Affichage de la liste des clients
    When I am on "/compta/client"
    And the response status code should be 200


  Scenario: Créer un client
    When I am on "/compta/client"
    And I follow "Créer un nouveau client"
    Then I should be on "/compta/client/new"
    And the response status code should be 200
    And I fill in the following:
      | Societe    | Philibert                   |
      | Nom        | Loïc Fremont                |
      | Email      | jedisjeux@jedisjeux.net     |
      | Rue        | 2 allée de la châtaigneraie |
      | Complement | bât C002                    |
      | Codepostal | 35740                       |
      | Ville      | Pacé                        |
    And I press "Créer"
    And I should be on "/compta/client/"
    And I should see "Philibert"
    And I should see "Modifier"
    And I should see "Supprimer"

  Scenario: Modifier un client
    When I am on "/compta/client"
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
    And the response status code should be 200
    And I fill in the following:
      | Societe | Ludibay |
    And I press "Enregistrer"
    And I should be on "/compta/client/"
    And I should see "Ludibay"
    And I should not see "Philibert"

  Scenario: Supprimer un produit
    When I am on "/compta/client"
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
    And I press "Supprimer"
    And I should be on "/compta/client/"
    And the response status code should be 200
    And I should not see "Philibert"