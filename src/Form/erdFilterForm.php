<?php
namespace Drupal\entity_reference_diagram\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class erdFilterForm extends FormBase {

  public function getFormId() {
    return 'ERD Filter';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $bundles = array_shift($form_state->getBuildInfo()['args']);
    $form['filter'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Filters'),
      '#description' => $this->t('There might be an situation that lots references are just for easy accesses to other content. They are not meant to define the real "architecture level" entity relationship. You can filter out those unnecessary relationships to have a much cleaner and more meanningful entity relationship diagram.')
    );
    $form['filter']['entity_ref_fields'] = array(
      '#type' => 'checkboxes',
      '#options' => $bundles,
      '#title' => '',
      '#default_value' => !empty($_SESSION['entity_ref_fields']) ? $_SESSION['entity_ref_fields'] : array()
    );
    $form['filter']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('filter'),
    );
    $form['filter']['reset'] = array(
      '#type' => 'submit',
      '#value' => $this->t('reset'),
      '#submit' => array($this, 'erd_filter_reset')
    );
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity_ref_fields = $form_state->getValue('entity_ref_fields');
    $entity_ref_fields = array_filter($entity_ref_fields);
    if(empty($entity_ref_fields)) {
      unset($_SESSION['entity_ref_fields']);
    } else {
      $_SESSION['entity_ref_fields'] = $entity_ref_fields;
    }
  }

  public function erd_filter_reset(array &$form, FormStateInterface $form_state) {
    unset($_SESSION['entity_ref_fields']);
  }
}