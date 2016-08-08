<?php
namespace Craft;

class PalettePlugin extends BasePlugin {
  public function getName() {
    return Craft::t('Palette');
  }

  public function getVersion() {
    return '1.0';
  }

  public function getDeveloper() {
    return 'Yello Studio';
  }

  public function getDeveloperUrl() {
    return 'http://yellostudio.co.uk';
  }

  public function getDocumentationUrl() {
    return 'https://github.com/marknotton/craft-plugin-palette';
  }

  public function getReleaseFeedUrl() {
    return 'https://raw.githubusercontent.com/marknotton/craft-plugin-palette/master/palette/releases.json';
  }

  public function init() {
    if ( craft()->request->isCpRequest())  {
      if( craft()->userSession->isLoggedIn() ) {
        // Once the user is logged into the control panel, include this craft js file
        craft()->templates->includeCssResource("palette/palette.css");
        craft()->templates->includeJsResource('palette/vibrant.js');
        craft()->templates->includeJsResource('palette/palette.js');
       }
    } else {
      // Front end, create a collection of global variables
      craft()->urlManager->setRouteVariables(
        array('palette' => craft()->palette)
      );
    }
  }
}
