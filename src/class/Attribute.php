<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Attribute
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string $Name
 * @property string  $Value
 */
class Attribute extends Base
{
    #region member
    /**
     * Name.
     *
     * @var string
     */
    private $name;
    /**
     * Value.
     *
     * @var string
     */
    private $value;
    #endregion

    #region constructor
    /**
     * Constructor.
     *
     * @param string $name
     * @param string $value
     *
     * @return Attribute
     */
    public function __construct($name = '', $value = '')
    {
        $this->name = $name;
        $this->value = $value;
    }
    #endregion

    #region methods
    #endregion

    #region properties
    /**
     * Get name.
     *
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * Get value.
     *
     * @return string
     */
    protected function getValue()
    {
        return $this->value;
    }
    #endregion
}
