<?php

/**
 * Implements hook_preprocess_page().
 */
function chiron_preprocess_page(&$variables) {
  global $theme;

  $header = drupal_get_http_header('status');
  $theme_path = drupal_get_path('theme', 'chiron');
  $theme_url = url($theme_path);

  // Set templates of custom error pages.
  if (strcasecmp($header, '404 Not Found') === 0) {
    $variables['theme_hook_suggestions'][] = 'page__404';
  }
  elseif (strcasecmp($header, '403 Forbidden') === 0) {
    $variables['theme_hook_suggestions'][] = 'page__403';
  }

  // Add theme settings.
  drupal_add_js(array(
    $theme => array(
      'path' => $theme_path,
      'url' => $theme_url,
    )), 'setting');
}
