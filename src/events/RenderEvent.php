<?php
/**
 * @link      http://fork.de
 * @copyright Copyright (c) 2018 Fork Unstable Media GmbH
 */

namespace fork\renderer\events;

use craft\base\ElementInterface;
use yii\base\Event;

/**
 * Render event class.
 *
 * @author Fork Unstable Media GmbH
 * @since     1.0.2
 */
class RenderEvent extends Event
{
    // Properties
    // =========================================================================

    public $renderData;

}
