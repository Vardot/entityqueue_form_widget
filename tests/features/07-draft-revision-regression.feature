@entityqueue_form_widget @regression
Feature: A queued published node stays queued when a draft revision is saved
  As a content team
  I want a published, queued node to remain in its queue after I save an
  unpublished draft revision
  So that editing a draft never silently drops live content from a queue.

  # Guards the fix for the draft-revision regression (issue #3611115): saving
  # an unpublished draft of a node that already has a published revision must
  # not remove it from the queues it belongs to.

  Background:
    Given I am logged in as the "eqfw_editor" user

  Scenario: Saving an unpublished draft keeps the published node in the queue
    # Publish a node into the Test Queue.
    When I go to "/node/add/test_content"
    And I fill in "Title" with "Draft regression article"
    And I click "Entityqueues settings"
    And I check the checkbox "#test_queue"
    And I press "Save"
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should see "Draft regression article"
    # Save an unpublished draft revision without touching the queue widget.
    When I go to "/admin/content"
    And I click "Edit" in the "Draft regression article" row
    And I uncheck the checkbox "#edit-status-value"
    And I press "Save"
    # The node MUST still be in the queue.
    When I go to "/admin/structure/entityqueue/test_queue/test_queue"
    Then I should see "Draft regression article"
