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
        Given this dealer sold "Schlauer Bauer" product on "http://www.example.com/puerto-rico" page
        And this dealer also sold "Meuterer" product on "http://www.example.com/modern-art" page
        And this dealer has no active subscription
        When I run import dealers prices command
        Then this dealer should have no product anymore

    @cli @todo
    Scenario: Removing products out of catalog
        Given there is also a product "Out Of Catalog"
        And this dealer sold "Out Of Catalog" product on "http://www.example.com/out-of-catalog" page
        And this dealer also sold "Meuterer" product on "http://www.example.com/modern-art" page
        When I run import dealers prices command
        Then this dealer should have a product "Schlauer Bauer" priced at "€6.50"
        And this dealer should also have a product "Meuterer" priced at "€7.50"
        But this dealer should not have a product "Out Of Catalog"
