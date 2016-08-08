<?php
namespace Craft;

class PaletteService extends BaseApplicationComponent {

  public function getEntry($id = null, $section = null) {
    $criteria = craft()->elements->getCriteria(ElementType::Entry);

    if (is_string($id)) {
      // Query an entry by slug
      $criteria->slug = $id;
    } else {
      // Use current page ID if one hasn't been defined
      if ( $id == null ) {
        $id = craft()->urlManager->getMatchedElement()->id;
      }
      // Query a entry by ID
      if (is_numeric($id) && intval($id) > 0) {
        $criteria->id = $id;
      }
    }

    $criteria->status = null;
    $criteria->limit = 1;

    $entry = $criteria->first();

    // If the section type can be defined, we know the criteria will not be a 'single'
    if ( isset($entry['section']['type']) ) {
      // So check wether the section does need to be declared, and that it is a string
      if ( $section != null && is_string($id)) {
        // Then set the defined section handle
        $criteria->section = $section;
      }
    } 

    if( $entry ) {
      return $entry;
    } 
  }

  // // Get a field
  //{{ mop.getField('important_information', 'home') }}
  public function getField($field, $ref = null, $section = null) {
    // If $ref only contains numbers, treat it as an entry ID and not a slug string.
    $ref = ctype_digit($ref) && !is_array($ref) ? intval($ref) : $ref;
    // If the $ref is an array, we assume an entry block has been parsed. So we query that object directly
    $entry = is_array($ref) ? $ref : $this->getEntry($ref, $section);
    
    //TODO: Fix the problem that occurs when the field type is a string only... 
    // This whole function needs a little more work/testing
    $entry = $entry->$field;
    
    if ( is_string($entry) ) {
      return $entry;
    } else if ( $entry ) {
      return $entry->first();
    } 

  }


  // Get Virbrant colours
  public function colours($fieldHandle, $id = null) {
    $transformHandle = !isset($transformHandle) ? 'thumb' : $transformHandle;
    
    $image = $this->getField($fieldHandle, $id);
    if ($image && file_exists(getcwd().$image->url)) {
      if ( !is_null($image->vibrant) ) {
        return $image->vibrant;
      }
    }

    // if ($image && file_exists(getcwd().$image->url)) {
    //   return $image->vibrant($transformHandle);
    // } else {
    //   if (  is_string($fallback)) {
    //     // If the fallback paramater is a string, assume it is a url to a specific fallback.
    //     return $fallback;
    //   } elseif ( $fallback == true ) {
    //     // If one isn't found, revert back to a defualt image.
    //     return '/assets/images/default-'.$transformHandle.'.'.$format;
    //   }
    // }
  }


}