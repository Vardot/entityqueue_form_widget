# Usage scenarios

This page describes the real-world editorial scenarios that the Entityqueue
Form Widget supports. Each scenario is backed by an executable
[webship-js](https://webship.co) feature file under
`tests/functional-acceptance/features/`, so the documentation and the automated
acceptance tests stay in sync. The feature file that guards each behavior is
linked at the end of every section.

## Add content to a queue from the node form

A privileged editor creating or editing a node sees an **Entityqueues
settings** panel in the form sidebar. Opening it reveals a checkbox for every
queue that accepts this content type. Ticking a checkbox and saving the node
adds it to that queue; unticking a checkbox and saving removes it again — all
without leaving the content form.

- Simple (single) queue: `features/01-simple-queue-widget.feature`
- Add and remove round trip: `features/03-add-and-remove-from-queue.feature`

## Choose a subqueue on a multiple-subqueue queue

When a queue is configured with the *multiple subqueues* handler, the widget
lists every subqueue using a **"Subqueue (Queue)"** label — for example
*"Subqueue 1 (Test Multiple subqueues)"* — so the editor can tell which
subqueue each checkbox belongs to.

- Feature file: `features/02-multiple-subqueues-widget.feature`

## Per-queue permissions

The widget only shows a queue to editors who may change it. An editor who has
neither the *Manipulate all queues* permission nor the per-queue *Manipulate
&lt;queue&gt; queue* permission does not see that queue's checkbox, even though
they can still create and edit the content itself.

- Feature file: `features/04-permission-gating.feature`

## Full queues are shown as disabled

When a queue has reached its configured maximum size, its checkbox is shown
**disabled** and labelled with the current count, for example *"Test Limited
Queue (1 out of 1 items)"*. This tells the editor why the content cannot be
added and keeps the queue within its size limit. Queues acting as a rotating
queue (act as queue) are not disabled this way.

- Feature file: `features/05-full-queue-disabled.feature`

## Unpublished and draft content

Queues are meant to surface live content, so the widget is careful with
unpublished nodes:

- **A brand-new node saved unpublished is not added to a queue**, even if its
  checkbox is ticked. Publishing the node later (with the checkbox still
  ticked) adds it to the queue.
- **A published node that is already in a queue stays in that queue when a new
  unpublished draft revision is saved.** Saving a draft never silently drops
  live content out of a queue.

The second rule guards the fix for the draft-revision regression
([issue #3611115](https://www.drupal.org/i/3611115)).

- New unpublished node: `features/06-unpublished-new-node.feature`
- Draft revision of a queued node: `features/07-draft-revision-regression.feature`

## Related

- [Using the widget](1-using-widget.md)
- [Workflow](2-workflow.md)
- [Automated testing](../3-developers/4-automated-testing.md)
