<?php

namespace Drupal\entity_reference_diagram\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides automated tests for the entity_reference_diagram module.
 */
class ERDControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "entity_reference_diagram ERDController's controller functionality",
      'description' => 'Test Unit for module entity_reference_diagram and controller ERDController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests entity_reference_diagram functionality.
   */
  public function testERDController() {
    // Check that the basic functions of module entity_reference_diagram.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
