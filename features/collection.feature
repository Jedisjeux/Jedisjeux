@collection
Feature: Affichage de la fiche de jeu

  Background:
    Given there are following users:
      | user_id | username | email                | password | enabled |
      | 1       | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | 2       | superseb | blue@gmail.com       | superseb | yes     |
    And there are collections:
      | name            | description | username  |
      | ma super liste  | 12          | loic_425  |
      | une top liste   | 12          | superseb  |
    And there are default status
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
      | Modern Art  | 12      | 3          | 5          |
      | El Grande   | 12      | 2          | 5          |
      | Citadelles  | 10      | 2          | 8          |
    And collection "ma super liste" has following games:
      | Puerto Rico |
      | Modern Art  |
      | El Grande  |


  Scenario: Ajouter/supprimer un jeu à la ludothèque
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Ajouter à ma ludothèque"
    And I wait "1" seconds
    Then I should see "Le Jeu a été ajouté à votre ludothèque."
    When I press "Ajouter à ma ludothèque"
    And I wait "1" seconds
    Then I should see "Le Jeu a été supprimé de votre ludothèque."


  Scenario: Ajouter/supprimer un jeu aux envies
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Ajouter à mes envies"
    And I wait "1" seconds
    Then I should see "Le Jeu a été ajouté à votre liste d'envie."
    When I press "Ajouter à mes envies"
    And I wait "1" seconds
    Then I should see "Le Jeu a été supprimé de votre liste d'envie."


  Scenario: Ajouter/supprimer un jeu aux jeux joués
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "j'y ai joué"
    And I wait "1" seconds
    Then I should see "Le Jeu a été ajouté à la liste de jeu auxquels vous avez joué."
    When I press "j'y ai joué"
    And I wait "1" seconds
    Then I should see "Le Jeu a été supprimé de la liste de jeu auxquels vous avez joué."


  Scenario: Ajouter/supprimer un jeu aux favoris
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Coup de coeur"
    And I wait "1" seconds
    Then I should see "Le Jeu a été ajouté à vos coup de coeur."
    When I press "Coup de coeur"
    And I wait "1" seconds
    Then I should see "Le Jeu a été supprimé de vos coup de coeur."


  Scenario: Ajouter jeu à une liste
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I follow "Ajouter à une liste"
    And I wait "3" seconds
    Then I should see "J'ajoute \"Puerto Rico\""
    And I select the "radio-new-list" radio button
    And I fill in the following:
      | Nom de ma liste | la lista local |
      | Description     | la lista esta muy local |
    When I press "add-list-collection"
    Then I should see "La liste a été créé."
    When I am on game "Puerto Rico"
    And I follow "Ajouter à une liste"
    And I wait "1" seconds
    Then I should see "J'ajoute \"Puerto Rico\""
    And I should see "la lista local"
    And I check "la lista local"
    When I press "Ajouter"
    And I wait "1" seconds
    Then I should see "Le jeu a été rajoutée à mes listes."

  Scenario: aller sur mes listes
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "ma super liste"


  Scenario: Je ne peux pas voir les listes des autres
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | superseb |
      | Mot de passe      | superseb |
    And I press "Connexion"
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "une top liste"
    But I should not see "ma super liste"

  Scenario: aller sur une de mes listes
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "ma super liste"
    And I follow "ma super liste"
    And I should see "ma super liste"
    And I should see "Puerto Rico"
    And I should see "Modern Art"
    And I should see "El Grande"

  Scenario: aller sur une de mes listes/usergameattribute
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Coup de coeur"
    And I wait "1" seconds
    Then I should see "Le Jeu a été ajouté à vos coup de coeur."
    And I am on "/collection/mes-listes"
    Then I should see "Mes listes"
    And I should see "Mes coups de coeur"
    And I follow "Mes coups de coeur"
    Then I should see "Mes coups de coeur"
    And I should see "Puerto Rico"
