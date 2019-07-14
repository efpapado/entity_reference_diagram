<?php
namespace Drupal\entity_reference_diagram\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;

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
    // $entity_type = \Drupal::entityManager()->getDefinition('node');
    $bundles = entity_get_bundles();
    // ksm($bundles);
    // ksm(field_entity_bundle_field_info($entity_type, 'account', array()));

    $relations = array();
    $formatted_relations = array();
    $regsitered_relations = array();
    $relation_strings = array();
    $bundlesString = [];

    foreach($bundles AS $entityType => $bundleArray) {
      foreach($bundleArray AS $bundleName => $notUsed) {
        $fields_info = field_entity_bundle_field_info(\Drupal::entityManager()->getDefinition($entityType), $bundleName, array());
        if(empty($fields_info)) continue;
        $bundlesString[$bundleName] = $bundleName;
        foreach($fields_info AS $fieldinfo) {
          if(!empty($fieldinfo) && in_array($fieldinfo->get('field_type'), ['entity_reference', 'entity_reference_revisions'])) {
            $targetBundles = $fieldinfo->get('settings')['handler_settings']['target_bundles'];
            if(!empty($targetBundles)) {
              foreach($targetBundles AS $targetBundleName) {
                if(!empty($_SESSION['entity_ref_fields']) && empty($_SESSION['entity_ref_fields'][$bundleName]) && empty($_SESSION['entity_ref_fields'][$targetBundleName])) {
                  continue;
                }
                $this->_formatRelations($entityType, $fieldinfo->get('field_name'), $bundleName, $targetBundleName);
              }
            }
          }
        }
      }
    }
    $form = \Drupal::formBuilder()->getForm('Drupal\entity_reference_diagram\Form\erdFilterForm', $bundlesString);
    $info = '<p>' . t('The relationship is composited as: ') . '<b>' . t('[entity_reference_fieldname]@[type]:[bundle] -> [target_bundle]') . '</b></p>';
    $element = array(
      '#markup' => $info . '<div id="chart_div" class="dragscroll"></div>',
      '#attached' => array(
        'library' => array(
          'entity_reference_diagram/erdInterface'
        ),
        'drupalSettings' => [
          'erd' => [
            'entity_relations' => Json::encode($this->_cachedRelationMaps)
          ]
        ]
      )
    );
    return array($form, $element);
  }

  private function _formatRelations($entityType, $fieldName, $bundleName, $targetBundleName) {
    $relation_statement = $this->_getRelationDirection($bundleName, $targetBundleName);
    $relation_desc = "$fieldName@$entityType:" . $relation_statement;
    if(!$this->_isRelationCached(array($bundleName, $targetBundleName))) {
      $this->_cachedRelationMaps[$relation_statement] = array(
        'desc' => array($relation_desc),
        'self' => $targetBundleName,
        'parent' => $bundleName
      );
    }
    else {
      $this->_cachedRelationMaps[$relation_statement]['desc'][] = $relation_desc;
    }
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