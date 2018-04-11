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
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.renderer.render }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.renderer.render(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function render($template, $data)
    {
        return Template::raw(Renderer::$plugin->render->render($template, $data));
    }
}
