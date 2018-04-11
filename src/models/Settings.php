<?php
/**
 * Renderer plugin for Craft CMS 3.x
 *
 * Render html content from the fork ui pattern lib within templates (via json POST request)
 *
 * @link      http://fork.de
 * @copyright Copyright (c) 2018 Fork Unstable Media GmbH
 */

namespace fork\renderer\models;

use fork\renderer\Renderer;

use Craft;
use craft\base\Model;

/**
 * Renderer Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Fork Unstable Media GmbH
 * @package   Renderer
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * The url to the Pattern Lib Components renderer (e.g. http://frontend:5000/components/ if you use it on your local machine)
     *
     * @var string
     */
    public $patternLibUrls;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['patternLibUrls', 'required'],
        ];
    }
}
