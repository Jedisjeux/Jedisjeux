@managing_article_reviews
Feature: Browsing the reviews of an article
    In order to see what the members think about an article
    As an Administrator
    I want to browse the reviews of an article

    Background:
        Given there are default taxonomies for articles
        And there is an article "Cannes 2018" written by "kevin@example.com"
        And customer "alice@example.com" rated this article 4
        And there is an article "Essen 2017" written by "kevin@example.com"
        And customer "alice@example.com" rated this article 8
        And customer "bob@example.com" also rated this article 6
        And I am a logged in administrator

    @ui
    Scenario: Browsing only the reviews of an article
        When I want to browse the reviews of this article
        Then there should be 2 reviews of this article in the list
