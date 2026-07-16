@entityqueue_form_widget @permissions
Feature: The widget respects entityqueue permissions
  As a site builder
  I want the queue checkboxes hidden from editors without queue permissions
  So that only trusted roles can change queue membership.

  Scenario: An editor without entityqueue permission does not see the queues
    Given I am logged in as the "eqfw_basic" user
    When I go to "/node/add/test_content"
    Then I should not see "Test Queue"
     And I should not see "Test Limited Queue"

  Scenario: A privileged editor does see the queues on the same form
    Given I am logged in as the "eqfw_editor" user
    When I go to "/node/add/test_content"
    And I click "Entityqueues settings"
    Then I should see "Test Queue"
