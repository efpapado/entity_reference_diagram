<?php
namespace Drupal\entity_reference_diagram\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class erdController extends ControllerBase {

  private $_cachedRelationMaps;

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function erdPage() {
    $entity_manager = \Drupal::entityManager();
    $entity_type = \Drupal::entityManager()->getDefinition('node');
    $bundles = entity_get_bundles();
    // ksm($bundles);
    // ksm(field_entity_bundle_field_info($entity_type, 'account', array()));

    $relations = array();
    $formatted_relations = array();
    $regsitered_relations = array();
    $relation_strings = array();

    foreach($bundles AS $entityType => $bundleArray) {
      foreach($bundleArray AS $bundleName => $notUsed) {
        $field_info = field_entity_bundle_field_info($entityType, $bundleName, array());
        if($field_info->get('field_type') === 'entity_reference') {
          $this->
        }
      }
    }

    $element = array(
      '#markup' => 'Hello, world',
    );
    return $element;
  }

  private function _formatRelations() {

  }

  private function _isRelationCached($peers) {
    $relation = $this->_getRelationDirection($peers[0], $peers[1]);
    if(empty($this->_cachedRelationMaps[$relation])) {
      if(empty($this->_cachedRelationMaps[$relation])) {
        return false;
      }
      return false;
    }
    return true;
  }

  private function _getRelationDirection($src, $dest) {
    return "$src -> $dest";
  }
}