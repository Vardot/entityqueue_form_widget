@entityqueue_form_widget @critical
Feature: Entityqueue widget on the node form for multiple subqueues
  As a privileged editor
  I want every subqueue of a multiple-subqueue queue listed on the form
  So that I can choose which subqueue to push the content to.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: The widget lists each subqueue in "Subqueue (Queue)" format
    When I go to "/node/add/queued_content"
    Then I should see "Entityqueues settings"
    When I click "Entityqueues settings"
    Then "#subqueue_1" should be visible
     And I should see "Subqueue 1 (Test Multiple subqueues)"
     And I should see "Subqueue 2 (Test Multiple subqueues)"
     And I should see "Subqueue 3 (Test Multiple subqueues)"
