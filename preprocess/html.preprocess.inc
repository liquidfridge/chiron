<?php

/**
 * Implements hook_preprocess_html().
 */
function chiron_preprocess_html(&$variables) {
  $theme_path = drupal_get_path('theme', 'chiron');

  $variables['theme_info'] = array(
    'realpath' => $theme_path,
  );

  $header = drupal_get_http_header('status');

  // Set body classes of custom error pages.
  if (strcasecmp($header, '404 Not Found') === 0) {
    $variables['classes_array'][] = 'error-page';
    $variables['classes_array'][] = 'error-page-404';
  }
  elseif (strcasecmp($header, '403 Forbidden') === 0) {
    $variables['classes_array'][] = 'error-page';
    $variables['classes_array'][] = 'error-page-403';
  }
}
