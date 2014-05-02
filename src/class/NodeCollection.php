<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\GenericCollection;

/**
 * NodeCollection
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
class NodeCollection extends GenericCollection
{
    public function __construct()
    {
        parent::__construct('Com\PaulDevelop\Library\Modeling\Xml\INode');
    }
}
