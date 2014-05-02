<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\GenericCollection;

/**
 * AttributeCollection
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
class AttributeCollection extends GenericCollection
{
    public function __construct()
    {
        parent::__construct('Com\PaulDevelop\Library\Modeling\Xml\Attribute');
    }
}
