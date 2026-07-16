<?php

declare(strict_types=1);

namespace Drupal\Tests\entityqueue_form_widget\Unit;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entityqueue\EntityQueueInterface;
use Drupal\entityqueue\EntitySubqueueInterface;
use Drupal\entityqueue_form_widget\Hook\EntityqueueFormWidgetHooks;
use Drupal\node\NodeInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Unit tests for the Entityqueue Form Widget hook service.
 *
 * Exercises the pure filtering logic of getAllowedSubqueueList(): a subqueue is
 * offered on a node form only when its queue targets the node's entity type and
 * either targets no specific bundle or targets the node's bundle. Subqueues
 * whose queue is missing are skipped.
 */
#[Group('entityqueue_form_widget')]
#[CoversClass(EntityqueueFormWidgetHooks::class)]
class EntityqueueFormWidgetHooksTest extends UnitTestCase {

  /**
   * Builds the hook service with a storage that returns the given subqueues.
   *
   * @param \Drupal\entityqueue\EntitySubqueueInterface[] $subqueues
   *   The subqueues the entity_subqueue storage should return.
   *
   * @return \Drupal\entityqueue_form_widget\Hook\EntityqueueFormWidgetHooks
   *   The service under test.
   */
  protected function buildService(array $subqueues): EntityqueueFormWidgetHooks {
    $storage = $this->createMock(EntityStorageInterface::class);
    $storage->method('loadMultiple')->willReturn($subqueues);

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $entity_type_manager->method('getStorage')
      ->with('entity_subqueue')
      ->willReturn($storage);

    return new EntityqueueFormWidgetHooks(
      $this->createMock(AccountInterface::class),
      $entity_type_manager,
      $this->createMock(Connection::class),
    );
  }

  /**
   * Builds a mocked subqueue that returns the given queue and id.
   */
  protected function makeSubqueue(string $id, ?EntityQueueInterface $queue): EntitySubqueueInterface {
    $subqueue = $this->createMock(EntitySubqueueInterface::class);
    $subqueue->method('id')->willReturn($id);
    $subqueue->method('getQueue')->willReturn($queue);
    return $subqueue;
  }

  /**
   * Builds a mocked queue with the given entity settings.
   *
   * @param string $target_type
   *   The target entity type id.
   * @param string[] $target_bundles
   *   The target bundles keyed by bundle machine name. Empty means all.
   * @param bool $act_as_queue
   *   The "act as queue" flag value.
   */
  protected function makeQueue(string $target_type, array $target_bundles, bool $act_as_queue): EntityQueueInterface {
    $queue = $this->createMock(EntityQueueInterface::class);
    $queue->method('getEntitySettings')->willReturn([
      'target_type' => $target_type,
      'handler_settings' => $target_bundles ? ['target_bundles' => $target_bundles] : [],
    ]);
    $queue->method('getActAsQueue')->willReturn($act_as_queue);
    return $queue;
  }

  /**
   * Builds a mocked node with the given entity type and bundle.
   */
  protected function makeNode(string $entity_type, string $bundle): NodeInterface {
    $node = $this->createMock(NodeInterface::class);
    $node->method('getEntityTypeId')->willReturn($entity_type);
    $node->method('bundle')->willReturn($bundle);
    return $node;
  }

  /**
   * A queue targeting the node's type and bundle is allowed.
   */
  public function testMatchingBundleQueueIsAllowed(): void {
    $queue = $this->makeQueue('node', ['test_content' => 'test_content'], FALSE);
    $service = $this->buildService(['q_match' => $this->makeSubqueue('q_match', $queue)]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertArrayHasKey('q_match', $result);
    $this->assertSame('q_match', $result['q_match']['id']);
    $this->assertFalse($result['q_match']['act_as_queue_status']);
  }

  /**
   * A queue whose target bundles exclude the node's bundle is not allowed.
   */
  public function testExcludedBundleQueueIsNotAllowed(): void {
    $queue = $this->makeQueue('node', ['article' => 'article'], FALSE);
    $service = $this->buildService(['q_excluded' => $this->makeSubqueue('q_excluded', $queue)]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertArrayNotHasKey('q_excluded', $result);
    $this->assertSame([], $result);
  }

  /**
   * A subqueue whose queue is missing (NULL) is skipped.
   */
  public function testSubqueueWithoutQueueIsSkipped(): void {
    $service = $this->buildService(['q_orphan' => $this->makeSubqueue('q_orphan', NULL)]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertSame([], $result);
  }

  /**
   * A queue with no target bundles is allowed for any bundle of the type.
   */
  public function testEmptyTargetBundlesAllowsAnyBundle(): void {
    $queue = $this->makeQueue('node', [], TRUE);
    $service = $this->buildService(['q_any' => $this->makeSubqueue('q_any', $queue)]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'landing_page'));

    $this->assertArrayHasKey('q_any', $result);
    $this->assertTrue($result['q_any']['act_as_queue_status']);
  }

  /**
   * A queue targeting a different entity type is not allowed.
   */
  public function testDifferentTargetTypeIsNotAllowed(): void {
    $queue = $this->makeQueue('user', [], FALSE);
    $service = $this->buildService(['q_user' => $this->makeSubqueue('q_user', $queue)]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertSame([], $result);
  }

  /**
   * A mix of matching, excluded and orphan subqueues is filtered correctly.
   */
  public function testMixedSubqueuesAreFilteredCorrectly(): void {
    $subqueues = [
      'q_match' => $this->makeSubqueue('q_match', $this->makeQueue('node', ['test_content' => 'test_content'], FALSE)),
      'q_excluded' => $this->makeSubqueue('q_excluded', $this->makeQueue('node', ['article' => 'article'], FALSE)),
      'q_orphan' => $this->makeSubqueue('q_orphan', NULL),
      'q_wrong_type' => $this->makeSubqueue('q_wrong_type', $this->makeQueue('user', [], FALSE)),
    ];
    $service = $this->buildService($subqueues);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertSame(['q_match'], array_keys($result));
  }

  /**
   * With no subqueues at all the allowed list is empty.
   */
  public function testNoSubqueuesReturnsEmptyList(): void {
    $service = $this->buildService([]);

    $result = $service->getAllowedSubqueueList($this->makeNode('node', 'test_content'));

    $this->assertSame([], $result);
  }

}
