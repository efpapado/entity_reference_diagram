<?php

namespace Drupal\entity_reference_diagram\Controller;

use Drupal\Console\Command\Shared\TranslationTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\field\Entity\FieldConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ERDController.
 */
class ERDController extends ControllerBase {

  use TranslationTrait;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var ContentEntityType[]
   */
  private $entityDefinitions = [];

  /**
   * @var EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * @var EntityTypeBundleInfoInterface
   */
  private $entityTypeBundleInfo;

  /**
   * @var array
   */
  private $entityTypeBundles = [];

  /**
   * @var array
   */
  private $entityReferences = [];

  /**
   * @var array
   */
  private $entityReferencesInverse = [];

  /**
   * @var FieldConfig[]
   */
  private $fieldConfigs;


  /**
   * Constructs a new ERDController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, EntityTypeBundleInfoInterface $entity_type_bundle_info) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;

    foreach ($this->entityTypeManager->getDefinitions() as $entity_definition) {
      if (!$entity_definition instanceof ContentEntityType) {
        continue;
      }

      $entity_type_id = $entity_definition->id();
      $this->entityDefinitions[$entity_type_id] = $entity_definition;

      $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
      $this->entityTypeBundles[$entity_type_id] = $bundles;

      foreach ($bundles as $bundle_id => $bundle_data) {
        $bundle_fields = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle_id);

        foreach ($bundle_fields as $field_name => $field_definition) {
          $field_type = $field_definition->getType();
          if ($field_type !== 'entity_reference') {
            continue;
          }
          $field_config = FieldConfig::loadByName($entity_type_id, $bundle_id, $field_name);
          $this->fieldConfigs["$entity_type_id.$field_name"] = $field_config;
          if (!$field_config) {
            continue;
          }
          $settings = $field_config->getSettings();
          $target_entity_type_id = $settings['target_type'];
          $this->entityReferences[$entity_type_id][$field_name] = $target_entity_type_id;
          $this->entityReferencesInverse[$target_entity_type_id]["{$entity_type_id}:{$field_name}"] = $entity_type_id;
        }

      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_field.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * Renders the entity references in a simple table.
   *
   * @return array
   *   Render array.
   */
  public function table() {
    $header = [
      'host_id' => 'Host ID',
      'host_label' => 'Host label',
      'field_name' => 'Field name',
      'field_label' => 'Field label',
      'target_id' => 'Target ID',
      'target_label' => 'Target label',
    ];
    $rows = [];
    foreach ($this->entityReferences as $entity_type_id => $fields) {
      foreach ($fields as $field_name => $target_entity_type_id) {
        $key = "{$entity_type_id}.{$field_name}";
        $rows[] = [
          $entity_type_id,
          $this->entityDefinitions[$entity_type_id]->getLabel(),
          $field_name,
          $this->fieldConfigs[$key]->getLabel(),
          $target_entity_type_id,
          $this->entityDefinitions[$target_entity_type_id]->getLabel(),
        ];
      }
    }
    $table = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#sticky' => TRUE,
      '#empty' => $this->t('There are no entity reference fields.'),
      '#attributes' => [
        'id' => 'erd-simple-table',
      ],
    ];
    $table['#attached']['library'][] = 'entity_reference_diagram/simple_table';

    return [
      'table' => $table,
    ];
  }

}
