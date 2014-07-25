<?php
namespace raoul2000\widget\scrollup;

use yii\web\AssetBundle;

/**
 *
 * @author Raoul <raoul.boulard@gmail.com>
 */
class ScrollupAsset extends AssetBundle
{

	public $depends = [
		'yii\web\JqueryAsset'
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . '/assets';

		if (defined('YII_DEBUG')) {
			$this->js = [
				'js/jquery.scrollUp.js'
			];
		} else {
			$this->js = [
				'js/jquery.scrollUp.min.js'
			];
		}
		return parent::init();
	}
}
