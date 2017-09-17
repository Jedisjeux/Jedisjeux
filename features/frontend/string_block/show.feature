@ui @frontend @stringBlock @show
Feature: As a visitor
    I need to be able to see a string block content

    Background:
        Given init doctrine phpcr repository
        And there are string blocks:
            | name        | body                                                         |
            | about       | <p>Jedisjeux est une association loi 1901.</p>               |
            | head-office | <p class="add">16 rue DOM François Plaine<br>35137 Bédée</p> |

    Scenario: See about block content
        When I am on homepage
        Then I should see "Jedisjeux est une association loi 1901."

    Scenario: See address block content
        When I am on homepage
        Then I should see "16 rue DOM François Plaine"
