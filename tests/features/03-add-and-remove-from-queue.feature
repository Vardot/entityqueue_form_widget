@entityqueue_form_widget @critical
Feature: Add and remove a node from a simple queue via the node form
  As a privileged editor
  I want to tick or untick a queue on the content form
  So that content is pushed to or pulled from the queue when I save.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: Ticking the queue adds the node and unticking removes it
    # Create a published node with the Test Queue ticked.
    When I go to "/node/add/test_content"
    And I fill in "Title" with "E2E queued article"
    And I click "Entityqueues settings"
    And I check the checkbox "#test_queue"
    And I press "Save"
    Then I should see "E2E queued article"
    # The node now appears among the Test Queue subqueue items.
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should see "E2E queued article"
    # Edit the node and untick the queue.
    When I go to "/admin/content"
    And I click "Edit" in the "E2E queued article" row
    And I click "Entityqueues settings"
    And I uncheck the checkbox "#test_queue"
    And I press "Save"
    Then I should see "E2E queued article"
    # The node is gone from the Test Queue subqueue items.
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should not see "E2E queued article"
