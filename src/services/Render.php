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
use fork\renderer\events\RenderEvent;
use fork\renderer\Renderer;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;
use yii\base\Event;

/**
 * Render Service
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Fork Unstable Media GmbH
 * @package   Renderer
 * @since     1.0.0
 */
class Render extends Component
{

    const EVENT_BEFORE_EXTRACT_DATA = 'beforeExtractData';

    // Public Methods
    // =========================================================================

    /**
     * Post json data to pattern lib and return html in template
     *
     * From any other plugin file, call it like this:
     *
     *     Renderer::$plugin->render->render()
     *
     * @param $data
     * @param string $componentType 'organisms'|'molecules' the type of component to render
     * @param $template
     * @return mixed
     */
    public function render($data, $componentType = 'organisms', $template = null)
    {
        // set template name by matrix block handle if not provided
        if (!$template && !empty($data['type'])) {
            $template = $data['type'];
        }

        // extract data
        $data = $this->getData($data);

        // get the patter lib url from settings (depending on craft environment)
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

        $url = "$baseUrl/$componentType/$template/$template.html";

        $client = new Client();
        try {
            return $client->post($url, ['json' => $data])->getBody();
        } catch (\Exception $e) {
            Craft::error($e->getMessage(), 'renderer');
            return '';
        }
    }

    /**
     * Get a prettyfied json representation of the data to use/copy paste into the pattern library for frontend development
     *
     * @param $data
     * @return string
     */
    public function dump($data) {
        // extract data
        $data = $this->getData($data);

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Data extraction (e.g. get fields from module)
     *
     * @param $data
     * @return array
     */
    private function getData($data) {

        // Fire a 'beforeExtractData' event
        if ($this->hasEventHandlers(self::EVENT_BEFORE_EXTRACT_DATA)) {
            $event = new RenderEvent([
                'renderData' => $data,
            ]);
            $this->trigger(self::EVENT_BEFORE_EXTRACT_DATA, $event);
            $data = $event->renderData;
        }

        // if data is no array but e.g. an matrix block, get its fields
        if ($data instanceof Element) {
            $data = $data->getFieldValues();
        }

        return $data;
    }
}
