<?php
/**
 * Renderer plugin for Craft CMS 3.x
 *
 * Render html content from the fork ui pattern lib within templates (via json POST request)
 *
 * @link      http://fork.de
 * @copyright Copyright (c) 2018 Fork Unstable Media GmbH
 */

namespace fork\renderer\variables;

use craft\helpers\Template;
use fork\renderer\Renderer;

use Craft;

/**
 * Renderer Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.renderer }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Fork Unstable Media GmbH
 * @package   Renderer
 * @since     1.0.0
 */
class RendererVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Render a content module with given data/object (template will be guessed from matrix block type handle)
     *
     *     {{ craft.renderer.render($mymodule) }}
     *
     * If you need to specify a template name:
     *
     *     {{ craft.renderer.render($mymodule, 'organisms', 'quote') }}
     *
     * @param $data
     * @param string $componentType 'organisms'|'molecules' the type of component to render
     * @param null $template optional template name (otherwise template will be guessed from matrix block type handle)
     * @return string
     */
    public function render($data, $componentType = 'organisms', $template = null)
    {
        return Template::raw(Renderer::$plugin->render->render($data, $componentType, $template));
    }

    /**
     * Dump a prettyfied json representation of the data to use/copy paste into the pattern library for frontend development
     *
     *     {{ craft.renderer.dump($data) }}
     *
     * @param $data
     * @return string
     */
    public function dump($data)
    {
        return Template::raw('<pre>' . htmlentities(Renderer::$plugin->render->dump($data)) . '</pre>');
    }
}
