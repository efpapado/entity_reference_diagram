<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
* Act on entity reference diagram's formatted relations before drawing.
*
* @param $relations
*   An array of entity reference diagram's formatted relations
*
* @see drupal_alter()
*/
function hook_entity_relations_alter($relations) {
  return $relations;
}

/**
 * @}
 */
