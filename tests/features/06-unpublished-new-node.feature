@entityqueue_form_widget @regression
Feature: Unpublished new content is kept out of a queue until published
  As a content team
  I want an unpublished new node to stay out of the queue
  So that queues never surface content that is not live yet.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: A new unpublished node is not queued, but publishing adds it
    # Create a new node, unpublished, with the Test Queue ticked.
    When I go to "/node/add/test_content"
    And I fill in "Title" with "Unpublished draft article"
    And I uncheck the checkbox "#edit-status-value"
    And I click "Entityqueues settings"
    And I check the checkbox "#test_queue"
    And I press "Save"
    # It must NOT be in the queue while unpublished.
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should not see "Unpublished draft article"
    # Publish it with the queue ticked and it becomes queued.
    When I go to "/admin/content"
    And I click "Edit" in the "Unpublished draft article" row
    And I check the checkbox "#edit-status-value"
    And I click "Entityqueues settings"
    And I check the checkbox "#test_queue"
    And I press "Save"
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should see "Unpublished draft article"
