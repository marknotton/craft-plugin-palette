<?php
namespace Craft;
class PaletteVariable {
  public function colours($fieldHandle, $id = null) {
    return craft()->palette->colours(func_get_args()); 
  }
}
