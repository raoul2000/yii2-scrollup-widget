<?php
namespace raoul2000\widget\scrollup;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Scrollup is a wrapper for the [Scrollup-JS Jquery Plugin](http://markgoodyear.com/2013/01/scrollup-jquery-plugin/).
 * See also the [Github repository)(https://github.com/markgoodyear/scrollup)
 */
class Scrollup extends Widget
{

	/**
	 * list of available scroll-up themes
	 */
	const THEME_IMAGE = 'image';
	const THEME_TAB = 'tab';
	const THEME_PILLS = 'pill';
	const THEME_LINK = 'link';

	/**
	 * List of available animation modes
	 */
	const ANIMATION_SLIDE = 'slide';
	const ANIMATION_FADE = 'fade';
	const ANIMATION_NONE = 'none';

	/**
	 *
	 * @var string built-in theme to apply to the scrollup widget.
	 */
	public $theme = self::THEME_IMAGE;

	/**
	 *
	 * @var array options for the Scrollup plugin
	 */
	public $pluginOptions = [];

	/**
	 *
	 * @var array list of supported built-in themes
	 */
	private $_supportedThemes = [
		self::THEME_IMAGE,
		self::THEME_LINK,
		self::THEME_PILLS,
		self::THEME_TAB
	];

	/**
	 * @var array list of supported animation mode
	 */
	private $_supportedAnimation = [
		self::ANIMATION_FADE,
		self::ANIMATION_NONE,
		self::ANIMATION_SLIDE
	];

	/**
	 * Chekcs validity of theme and animation options
	 */
	public function init()
	{
		parent::init();
		if (! empty($this->theme) && ! in_array($this->theme, $this->_supportedThemes)) {
			throw new InvalidConfigException('Unsupported built-in theme : ' . $this->theme);
		}
		if (isset($this->pluginOptions['animation']) && ! in_array($this->pluginOptions['animation'], $this->_supportedAnimation)) {
			throw new InvalidConfigException('Unsupported animation mode : ' . $this->pluginOptions['animation']);
		}
	}

	/**
	 * @see \yii\base\Widget::run()
	 */
	public function run()
	{
		$this->registerClientScript();
	}

	/**
	 * Registers the needed JavaScript and inject the JS initialization code.
	 *
	 * Note that if a supported theme is set, all css in the assets/css/theme folder are published
	 * but only the css for the theme is registred.Moreover, if the select theme is 'image', the
	 * 'scrollText plugin option is cleared.
	 */
	public function registerClientScript()
	{
		$view = $this->getView();

		if (isset($this->theme)) {
			$path = $view->getAssetManager()->publish(__DIR__ . '/assets/css/themes');
			$view->registerCSSFile($path[1] . '/' . $this->theme . '.css');

			if ($this->theme == 'image' && isset($this->pluginOptions['scrollText'])) {
				$this->pluginOptions['scrollText'] = '';
			}
		}

		ScrollupAsset::register($view);

		$options = empty($this->pluginOptions) ? '{}' : Json::encode($this->pluginOptions);
		$js = "$.scrollUp($options);";
		$view->registerJs($js);
	}
}
