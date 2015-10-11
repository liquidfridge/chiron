<?php

/**
 * Implements hook_preprocess_panels_pane().
 */
function chiron_preprocess_panels_pane(&$variables) {
  // Add panels-pane--[PANE]--[TYPE]--[SUBTYPE] template suggestion.
  $name = 'panels_pane__' . $variables['pane']->panel . '__' . $variables['pane']->type . '__' . $variables['pane']->subtype;
  $name = str_replace(array(':', '-'), '_', $name);
  $variables['theme_hook_suggestions'][] = $name;
}
