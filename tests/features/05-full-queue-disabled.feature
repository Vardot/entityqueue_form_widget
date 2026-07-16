@entityqueue_form_widget
Feature: A full queue disables its checkbox on the node form
  As a privileged editor
  I want a full queue to be shown as disabled with its item count
  So that I understand why I cannot add more content to it.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: The checkbox for a full queue is disabled and shows the count
    When I go to "/node/add/test_content"
    And I click "Entityqueues settings"
    Then "#test_limited_queue" should be disabled
     And I should see "Test Limited Queue"
     And I should see "1 out of 1 items"
    # The unlimited queue on the same form stays enabled.
    And "#test_queue" should be enabled
