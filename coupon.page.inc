<?php

/**
 * @file
 * Contains coupon.page.inc.
 *
 * Page callback for Coupon entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Coupon templates.
 *
 * Default template: coupon.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_coupon(array &$variables) {
  // Fetch Coupon Entity Object.
  $coupon = $variables['elements']['#coupon'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
