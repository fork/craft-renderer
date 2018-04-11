<?php
/**
 * Renderer plugin for Craft CMS 3.x
 *
 * Render html content from the fork ui pattern lib within templates (via json POST request)
 *
 * @link      http://fork.de
 * @copyright Copyright (c) 2018 Fork Unstable Media GmbH
 */

namespace fork\renderer\services;

use craft\base\Element;
use fork\renderer\Renderer;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;

/**
 * Render Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Fork Unstable Media GmbH
 * @package   Renderer
 * @since     1.0.0
 */
class Render extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Renderer::$plugin->render->render()
     *
     * @return mixed
     */
    public function render($template, $data)
    {
        if ($data instanceof Element) {
            $data = $data->getFieldValues();
        }

        $env = Craft::$app->config->env;
        $pluginSettings = Renderer::$plugin->getSettings();
        $urls = $pluginSettings->patternLibUrls;

        $envUrls = [];
        foreach ($urls as $url) {
            if ($env == $url[0]) {
                $envUrls[] = $url[1];
            }
        }

        $baseUrl = reset($envUrls);

        // TODO: molecules too...
        $url = "$baseUrl/organisms/$template/$template.html";

        $client = new Client();
        try {
            return $client->post($url, ['json' => $data])->getBody();
        } catch (\Exception $e) {
            Craft::error($e->getMessage(), 'renderer');
            return '';
        }
    }
}
