<?php
namespace Craft;

class Palette_AssetController extends BaseController {
  /**
  * Action to get an asset by it ID
  * --
  * @return json
  */
  public function actionGetAssetByID() {
    $this->requireAjaxRequest();

    $assetId = craft()->request->getRequiredPost('assetId');
    $asset = craft()->assets->getFileById($assetId);

    if (!$asset) {
      throw new Exception('Could not find a file with the ID '.$assetId);
    }

    $transform = array(
        "mode"=> "crop",
        "position"=>  "center-center",
        "height"=> NULL,
        "width"=> "300",
        "quality"=> "60",
    );

    $assetUrl = str_replace("{assetsUrl}","/assets",$asset->getUrl($transform));
    $assetTitle = $asset->title;

    $this->returnJson(
      array(
        'success' => true,
        'asset' => $assetUrl,
        'title' => $assetTitle,
      )
    );
  }
}