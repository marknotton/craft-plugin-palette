<?php
namespace Craft;

class PaletteFieldType extends BaseFieldType {

  public function getName() {
    return Craft::t('Palette');
  }

  protected function defineSettings() {
    return array(
      'limit' => array(AttributeType::Number, 'default' => 2),
    );
  }

  public function defineContentAttribute() {
    return AttributeType::Mixed;
  }

  public function getSettingsHtml() {
    return craft()->templates->render('palette/settings', array(
      'settings' => $this->getSettings()
    ));
  }

  public function getInputHtml($name, $value) {
    $colour1 = (isset($value['colour1']) ? $value['colour1'] : '');
    $colour2 = (isset($value['colour2']) ? $value['colour2'] : '');
    $colour3 = (isset($value['colour3']) ? $value['colour3'] : '');
    $colour4 = (isset($value['colour4']) ? $value['colour4'] : '');
    $colour5 = (isset($value['colour5']) ? $value['colour5'] : '');

    return craft()->templates->render('palette/input', array(
      'name'     => $name,
      'colour1'  => $colour1,
      'colour2'  => $colour2,
      'colour3'  => $colour3,
      'colour4'  => $colour4,
      'colour5'  => $colour5,
      'settings' => $this->getSettings()
    ));
  }
}