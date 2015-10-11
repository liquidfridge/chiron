<?php
// Ensure Metatag module adds its content.
render($page['content']['metatags']);
?>

<div class="l-page">
  <header class="l-header" role="banner" id="header">
    <div class="l-branding">
      <div class="l-logo">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="icon-logo">
          <?php
          print chiron_icon(array(
            'id' => 'logo',
            'title' => t('Home'),
          ));
          ?>
        </a>
      </div>
    </div>
    <?php print render($page['header']); ?>
    <?php print render($page['navigation']); ?>
  </header>

  <div class="l-main">
    <div class="l-content" role="main">
      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>
    </div>
  </div>

  <footer class="l-footer" role="contentinfo">
    <?php print render($page['footer']); ?>
  </footer>
</div>
