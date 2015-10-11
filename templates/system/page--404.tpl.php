<?php render($page['content']); ?>

<div class="l-page">
  <div class="l-main" id="main-content">
    <div class="l-content" role="main">
      <h1><?php print t('Page not found'); ?></h1>
      <p><?php print t('Please check the !front_page.', array('!front_page' => l(t('front page'), '/'))); ?></p>
    </div>
  </div>
</div>