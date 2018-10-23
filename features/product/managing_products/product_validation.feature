@managing_products
Feature: Products validation
    In order to avoid making mistakes when managing products
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given I am a logged in administrator

    @ui
    Scenario: Trying to add a new product without name
        Given I want to create a new product
        When I do not specify its name
        And I try to add it
        Then I should be notified that the name is required
        And this product should not be added

    @ui
    Scenario: Trying to add a new product with min player count greater than max player count
        Given I want to create a new product
        When I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I specify his min player count as "5"
        And I specify his max player count as "2"
        And I try to add it
        Then I should be notified that min player count value should not be greater than max value
        And this product should not be added

    @ui
    Scenario: Trying to add a new product with min player count equals to zero
        Given I want to create a new product
        When I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I specify his min player count as "0"
        And I try to add it
        Then I should be notified that min player count value should be one or more
        And this product should not be added

    @ui
    Scenario: Trying to add a new product with max player count equals to zero
        Given I want to create a new product
        When I specify his name as "Puerto Rico"
        And I specify his slug as "puerto-rico"
        And I specify his max player count as "0"
        And I try to add it
        Then I should be notified that max player count value should be one or more
        And this product should not be added

    @ui
    Scenario: Trying to add a new product as a staff user
        When I am a logged in staff user
        Then I should not be able to add product
