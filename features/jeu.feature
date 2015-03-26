@jeu
Feature: Affichage de la fiche de jeu

  Background:
    Given there are default status
    And there are games:
      | libelle     | age_min | joueur_min | joueur_max |
      | Puerto Rico | 12      | 2          | 5          |
      | Modern Art  | 12      | 3          | 5          |
      | El Grande   | 12      | 2          | 5          |
      | Citadelles  | 10      | 2          | 8          |
    And there are following users:
      | username | email                | password | enabled |
      | loic_425 | loic_425@hotmail.com | loic_425 | yes     |
      | blue     | blue@gmail.com       | blue     | yes     |
      | toto     | toto@gmail.com       | toto     | yes     |
    And user "loic_425" has following roles:
      | ROLE_ADMIN |
    And game "Puerto Rico" has following mechanisms:
      | Combinaisons |
      | Placement    |
    And game "Modern Art" has following mechanisms:
      | Enchères |
    And game "Puerto Rico" has following themes:
      | Commerce |
    And game "El Grande" has following themes:
      | Renaissance |
    And game "Puerto Rico" has following authors:
      | Andreas | Seyfarth |
    And game "Puerto Rico" has following illustrators:
      | Franz | Vohwinkel |
    And game "Puerto Rico" has following editors:
      |  | Alea         |
      |  | Ravensburger |


  Scenario: Affichage de la fiche de jeu
    When I am on game "Puerto Rico"
    Then I should see "Puerto Rico"
    And I should see "Un jeu de Andreas Seyfarth"
    And I should see "Illustré par Franz Vohwinkel"
    And I should see "édités par Alea, Ravensburger "
    And I should see "à partir de 12 ans"
    And I should see "de 2 à 5 joueurs"
    And I should see "Mécanismes : Combinaisons, Placement"
    And I should see "Thèmes : Commerce"
    #And I should see "Matériel"
    #And I should see "But du jeu"
    #And I should see "Description du jeu"

  Scenario: Acces à l'edition de la fiche interdit
    When I am on "/jeu/1/edit"
    Then I should be on "/login"

  Scenario: Lien vers un mécanisme et de la liste des jeux correspondants
    When I am on game "Modern Art"
    And I follow "Enchères"
    Then I should be on mechanism "Enchères"
    And I should see "Enchères"
    And I should see "Modern Art"
    But I should not see "Citadelles"

  Scenario: Lien vers un thème et de la liste des jeux correspondants
    When I am on game "El Grande"
    And I follow "Renaissance"
    Then I should be on theme "Renaissance"
    And I should see "Renaissance"
    And I should see "El Grande"
    But I should not see "Citadelles"
    And I should not see "Puerto Rico"

  Scenario: Lien vers un auteur et de la liste des jeux correspondants
    When I am on game "Puerto Rico"
    And I follow "Andreas Seyfarth"
    Then I should be on ludography of "Andreas Seyfarth"
    And I should see "Puerto Rico"
    But I should not see "Citadelles"
    And I should not see "El Grande"

  Scenario: Création d'une fiche de jeu
    Given I am on "/jeu/new"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on "/jeu/new"
    When I fill in the following:
      | Libelle | Jojo Lapin |
    And I press "Créer"
    Then I should be on game "Jojo Lapin"
    And I should see "Jojo Lapin"

  Scenario: Modification d'une fiche de jeu
    Given I am on edition page of the game "Puerto Rico"
    And I should be on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I should be on edition page of the game "Puerto Rico"
    When I fill in the following:
      | Libelle | Jojo Lapin |
    And I press "Modifier"
    Then I should see "Jojo Lapin"

  Scenario: Ajouter/supprimer un jeu à la ludothèque
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Ajouter à ma ludothèque"
    And I wait until loading has finished
    Then I should see "Le Jeu a été ajouté à votre ludothèque."
    When I press "Ajouter à ma ludothèque"
    And I wait until loading has finished
    Then I should see "Le Jeu a été supprimé de votre ludothèque."


  Scenario: Ajouter/supprimer un jeu aux envies
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Ajouter à mes envies"
    And I wait until loading has finished
    Then I should see "Le Jeu a été ajouté à votre liste d'envie."
    When I press "Ajouter à mes envies"
    And I wait until loading has finished
    Then I should see "Le Jeu a été supprimé de votre liste d'envie."


  Scenario: Ajouter/supprimer un jeu aux jeux joués
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "j'y ai joué"
    And I wait until loading has finished
    Then I should see "Le Jeu a été ajouté à la liste de jeu auxquels vous avez joué."
    When I press "j'y ai joué"
    And I wait until loading has finished
    Then I should see "Le Jeu a été supprimé de la liste de jeu auxquels vous avez joué."


  Scenario: Ajouter/supprimer un jeu aux favoris
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I press "Coup de coeur"
    And I wait until loading has finished
    Then I should see "Le Jeu a été ajouté à vos coup de coeur."
    When I press "Coup de coeur"
    And I wait until loading has finished
    Then I should see "Le Jeu a été supprimé de vos coup de coeur."


  Scenario: Ajouter jeu à une liste
    Given I am on "/login"
    And I fill in the following:
      | Nom d'utilisateur | loic_425 |
      | Mot de passe      | loic_425 |
    And I press "Connexion"
    And I am on game "Puerto Rico"
    When I follow "Ajouter à une liste"
    And I wait "1" seconds
    Then I should see "J'ajoute \"Puerto Rico\""
    And I fill in the following:
      | Nom de ma liste | la lista local |
      | Description     | la lista esta muy local |
    When I press "Ajouter"
    And I wait "1" seconds
    When I follow "Ajouter à une liste"
    And I wait "1" seconds
    Then I should see "J'ajoute \"Puerto Rico\""
    And I should see "la lista local"
    And I check "la lista local"
    When I press "Ajouter"
    And I wait "1" seconds
    Then I should see "Le jeu a été rajoutée à mes listes."