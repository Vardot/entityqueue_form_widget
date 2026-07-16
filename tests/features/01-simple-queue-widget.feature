@entityqueue_form_widget @critical
Feature: Entityqueue widget on the node form for a simple queue
  As a privileged editor
  I want the "Entityqueues settings" widget on the content add form
  So that I can push a node to a simple queue without leaving the form.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: The widget exposes the simple queue on the content add form
    When I go to "/node/add/test_content"
    Then I should see "Entityqueues settings"
    When I click "Entityqueues settings"
    Then "#test_queue" should be visible
     And I should see "Test Queue"
