@albums
Feature: Manage Albums
  In order to have my pictures organized
  As a logged in user
  I want to have albums

  Scenario: Create album
    Given I am logged in
    When I go to the "albums" page
    And I follow "New Album"
    And I fill in "Name" with "My Album"
    And I fill in "Description" with "Picture Album"
    And I press "Create"
    Then I should see "My Album"
    And I should see "Picture Album"
    Then I should see "Add Photos"

