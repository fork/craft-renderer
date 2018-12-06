# Renderer plugin for Craft CMS 3.x

Render html content from the fork ui pattern lib within templates (via json POST request)

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.
Also a running Pattern Library (https://github.com/fork/ui-pattern-library) nodejs environment is necessary.
You should include this as a git submodule in your project (in this case in the "frontend/" directory).
For use with Docker here is an example for docker-compose.yml:

```
version: '3'
services:
  mycraftinstance:
    ...
    links:
      - "frontend:frontend"
      - ...
    depends_on:
      - frontend
      - ...
    ...      

  frontend:
    image: "keymetrics/pm2:10-alpine"
    working_dir: /home/node/app
    ports:
      - 5000:5000
    volumes:
      - ./frontend/:/home/node/app
    command: sh -c 'yarn && pm2-runtime start pm2.json'
```


## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to use the repository:

        [make] composer config repositories.renderer vcs git@github.com:fork/craft-renderer.git

3. Then tell Composer to load the plugin:

        [make] composer require fork/renderer

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for Renderer.

## Renderer Overview

This plugin uses the Fork UI Pattern Library (https://github.com/fork/ui-pattern-library) to render components.
By sending json data via a POST Request to the Pattern Library it retrieves the html markup snippets for separate components (molecules or organisms).
These could be implemented in Craft as Matrix Blocks for example, but any data can be used.

## Configuring Renderer

Go to the Plugin settings (http://mysite.com/admin/settings/plugins/renderer) and define the Urls to the Pattern Library (based on craft environment).
For "dev", if you use the given docker example this would be `http://frontend:5000/components/`.
You can also set/override this setting in your site/.env file like this:
`PATTERNLIB_URL="http://localhost:5001/components/"`

### Events

To modify the data being sent, there is an "beforeExtractData" event. You can implement it in your custom plugin init method like this:

```
// Modify rendering data
Event::on(
    Render::class,
    Render::EVENT_BEFORE_EXTRACT_DATA,
    function (RenderEvent $event) {
        if (!empty($event->renderData['reference'])) {
            $entry = $event->renderData->reference->one();

            $returnFields = [];
            $fields = $entry->getFieldValues();
            foreach ($fields as $name => $field) {
                if ($field instanceof \craft\redactor\FieldData) {
                    $returnFields[$name] = $field->getRawContent();
                } elseif ($field instanceof \typedlinkfield\models\Link) {
                    $returnFields[$name] = [
                        'url' => $field->getUrl(),
                        'text' => $field->getText(),
                        'target' => $field->getTarget(),
                    ];
                } elseif ($field instanceof \craft\elements\db\AssetQuery) {
                    $returnFields[$name] = $field->one()->getUrl();
                } else {
                    $returnFields[$name] = $field;
                }
            }

            $event->renderData = $returnFields;
        }
    }
);
```

## Using Renderer

Here is an example on how to use renderer in your templates:

```
// rendering matrix blocks
// in this case your block type handle needs to match the name used in the pattern library!
{% for myModule in entry.myMatrixBlocks %}

    {{ craft.renderer.render(myModule) }}

{% endfor %}

// specifying the type and template name
{% for myModule in entry.myMatrixBlocks %}

    {{ craft.renderer.render(myModule, 'organisms', 'quote') }}

    // OR if the module is a "molecule" (and your block type handle matches the name in the pattern library)
    {{ craft.renderer.render(myModule, 'molecules') }}

{% endfor %}
```
It also provides a dump method to retrieve a prettified json representation to use/copy within the pattern library:
```
{{ craft.renderer.dump(data) }}
```

## Renderer Roadmap

Some things to do, and ideas for potential features:

* Release it (properly)

Brought to you by [Fork Unstable Media GmbH](http://fork.de)
