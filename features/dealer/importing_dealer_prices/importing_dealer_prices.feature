@importing_dealer_prices
Feature: Importing dealer prices
    In order to import dealer prices in the database
    As a developer
    I want to run import dealer prices command

    Background:
        Given there is dealer "Philibert"
        And this dealer has a price list with path "philibert.csv"
        And there is a product "Schlauer Bauer"
        And there is also a product "Meuterer"

    @cli
    Scenario: Importing dealer prices in database
        When I run import dealers prices command
        Then the command should finish successfully
        And this dealer should have a product "Schlauer Bauer" priced at "€6.50"
        And this dealer should also have a product "Meuterer" priced at "€7.50"

    @cli
    Scenario: Removing prices from a dealer with an inactive subscription
        When this dealer has no active subscription
        Then this dealer should have no product anymore
