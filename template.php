<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * chiron theme.
 */
// Menu styles.
define('CHIRON_MENU_ICON', 'icon');
define('CHIRON_MENU_ICON_TEXT', 'icon text');

/**
 * Implements hook_js_alter().
 */
function chiron_js_alter(&$js) {
  global $theme;

  $theme_path = path_to_theme();

  // Get max weight.
  $max_weight = 0;
  foreach ($js as $key => $value) {
    if (!empty($value['weight']) && $value['weight'] > $max_weight) {
      $max_weight = $value['weight'];
    }
  }

  // Move the main script to the footer. This script includes the config, which
  // should load after Drupal.settings has been set in the header.
  $search = array(
    $theme_path . '/js/' . $theme . '.js',
    $theme_path . '/js/' . $theme . '.min.js'
  );
  foreach ($js as $key => $value) {
    if (in_array($key, $search)) {
      $js[$key]['scope'] = 'footer';
      $js[$key]['weight'] = ++$max_weight;
    }
  }
}

/**
 *
 * @param array $opts
 * @return string
 */
function chiron_icon(array $opts) {
  $themes = list_themes();
  $colors = $themes['chiron']->info['settings']['icon']['colors'];
  $prefix = $themes['chiron']->info['settings']['icon']['prefix'];
  $size = $themes['chiron']->info['settings']['icon']['size'];

  if (empty($opts['color'])) {
    $opts['color'] = NULL;
  }

  $default_icon = array(
    'class_name' => 'default' . ($opts['color'] ? ' ' . $opts['color'] : ''),
    'id' => $opts['id'],
    'color' => $opts['color'],
    'fill' => empty($colors[$opts['color']]) ? NULL : $colors[$opts['color']],
    'width' => empty($opts['width']) ? $size : (int) $opts['width'],
    'height' => empty($opts['height']) ? $size : (int) $opts['height'],
  );

  $hover_icon = NULL;
  $has_hover = !empty($opts['hover_id']) || !empty($opts['hover_color']);

  if ($has_hover) {
    if (empty($opts['hover_color'])) {
      $opts['hover_color'] = $opts['color'];
    }

    $hover_icon = array(
      'class_name' => 'hover' . ($opts['hover_color'] ? ' ' . $opts['hover_color'] : ''),
      'id' => empty($opts['hover_id']) ? $opts['id'] : $opts['hover_id'],
      'color' => $opts['hover_color'],
      'fill' => empty($colors[$opts['hover_color']]) ? NULL : $colors[$opts['hover_color']],
      'width' => $default_icon['width'],
      'height' => $default_icon['height'],
    );
  }

  $attr = array(
    'class' => $prefix . ' ' . $prefix . '-' . $opts['id'] . ($has_hover ? ' has-hover' : '') . (isset($opts['class']) ? ' ' . $opts['class'] : ''),
    'title' => isset($opts['title']) ? $opts['title'] : '',
    'aria-hidden' => 'true',
    'draggable' => 'false',
    'ondragstart' => 'return false;',
  );

  // Use padding-bottom hack on container to preserve aspect ratio.
  // @see https://css-tricks.com/scale-svg
  $ratio = ($default_icon['height'] / $default_icon['width'] * 10000) / 100;
  if ($ratio !== 100) {
    $attr['style'] = 'height: 0; padding: 0 0 ' . $ratio . '% 0;';
  }

  $html = '<span' . drupal_attributes($attr) . '>';

  $html .= _chiron_icon_svg_symbol($default_icon);

  if (isset($hover_icon)) {
    $html .= _chiron_icon_svg_symbol($hover_icon);
  }

  $html .= '</span>';

  if (isset($opts['title'])) {
    $html .= '<span class="element-invisible">' . $opts['title'] . '</span>';
  }

  return $html;
}

/**
 * Prepares a menu tree for rendering with icons.
 *
 * @param array $menu
 * @param string $style CHIRON_MENU_* constant.
 * @param string $color
 * @param string $hover_color
 */
function chiron_icon_prepare_menu(&$menu, $style, $color, $hover_color = NULL) {
  foreach ($menu as $delta => $link) {
    if (is_numeric($delta)) {
      reset($link['#original_link']['options']['attributes']['class']);
      $id = current($link['#original_link']['options']['attributes']['class']);

      $clone = (object) $link;
      $key = '#title';
      $title = check_plain($clone->{$key});

      if ($style === CHIRON_MENU_ICON) {
        $menu[$delta]['#localized_options']['attributes']['title'] = $title;
        $menu[$delta]['#localized_options']['html'] = TRUE;

        $menu[$delta]['#title'] = chiron_icon(array(
          'id' => substr($id, 5),
          'color' => $color,
          'hover_color' => $hover_color,
          'title' => $title,
        ));
      }
      elseif ($style === CHIRON_MENU_ICON_TEXT) {
        $menu[$delta]['#localized_options']['html'] = TRUE;

        $menu[$delta]['#title'] = chiron_icon(array(
              'id' => substr($id, 5),
              'color' => $color,
              'hover_color' => $hover_color,
            )) . '<span class="text">' . $title . '</span>';
      }
    }
  }
}

/**
 *
 * @param array $opts
 * @return string
 */
function _chiron_icon_svg_symbol(array $opts) {
  $themes = list_themes();
  $prefix = $themes['chiron']->info['settings']['icon']['prefix'];

  return '<svg class="' . $opts['class_name'] . '"' . ($opts['fill'] ? ' fill="' . $opts['fill'] . '"' : '') . ' preserveAspectRatio="xMidYMid meet" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><use xlink:href="#' . $prefix . '-' . $opts['id'] . '"></use></svg>';
}

/**
 *
 * @param array $opts
 * @return string
 */
function _chiron_icon_svg(array $opts) {
  static $svg;
  static $symbols;

  if (!isset($svg)) {
    $theme_path = drupal_get_path('theme', 'chiron');
    $svg_path = $theme_path . '/svg/icon.svg';

    if (file_exists($svg_path)) {
      $data = file_get_contents($svg_path);
      $svg = new SimpleXMLElement($data);
      $svg->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');
      $svg->registerXPathNamespace('xlink', 'http://www.w3.org/1999/xlink');
    }
    else {
      $svg = FALSE;
    }
  }

  if (!isset($symbols)) {
    $symbols = array();
  }

  if ($svg === FALSE) {
    return '';
  }

  $themes = list_themes();
  $prefix = $themes['chiron']->info['settings']['icon']['prefix'];

  if (!isset($symbols[$opts['id']])) {
    $result = $svg->xpath("//svg:symbol[@id='" . $prefix . "-" . $opts['id'] . "']");

    if (empty($result)) {
      $symbols[$opts['id']] = FALSE;
    }
    else {
      $view_box = '0 0 ' . $opts['width'] . ' ' . $opts['height'];
      if (isset($result[0]->attributes()->viewBox)) {
        $view_box = (string) $result[0]->attributes()->viewBox;
      }

      $children = '';
      foreach ($result[0]->children() as $child) {
        $children .= $child->asXML();
      }

      $symbols[$opts['id']] = array(
        'view_box' => $view_box,
        'children' => $children,
      );
    }
  }

  if (empty($symbols[$opts['id']])) {
    return '';
  }

  return '<svg class="' . $opts['class_name'] . '"' . ($opts['fill'] ? ' fill="' . $opts['fill'] . '"' : '') . ' viewBox="' . $symbols[$opts['id']]['view_box'] . '" preserveAspectRatio="xMidYMid meet" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' . $symbols[$opts['id']]['children'] . '</svg>';
}
