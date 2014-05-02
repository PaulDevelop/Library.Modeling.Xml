<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

/**
 * INode
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
interface INode
{
    #region member
    #endregion

    #region methods
    /**
     * Convert to XML.
     *
     * @return string
     */
    public function toXml();
    #endregion

    #region properties

    /**
     * Nodes.
     *
     * @return NodeCollection
     */
    public function getNodes();
    #endregion
}
