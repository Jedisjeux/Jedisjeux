@viewing_people
Feature: Viewing the people of a zone
    In order to discover the authors of a country
    As a Visitor
    I want to browse the people of a zone

    Background:
        Given there are default taxonomies for people
        And there is a zone "France"
        And there is also a zone "Germany"
        And there is person with name "Bruno Cathala"
        And this person is from "France" zone
        And there is person with name "Reiner Knizia"
        And this person is from "Germany" zone

    @ui
    Scenario: Browsing the people of a zone
        When I browse the people from "France" zone
        Then I should see "Bruno Cathala" in this list
        But I should not see "Reiner Knizia" in this list
