<?php
namespace Craft;

use Twig_Extension;

class palette_globals extends \Twig_Extension {

  public function getName() {
    return Craft::t('Palette Globals');
  }

  public function getGlobals() {
    $globals = array(
      'palette' => craft()->palette,
    );

    return $globals;
  }
}
