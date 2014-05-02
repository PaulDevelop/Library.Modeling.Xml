<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\Base;

/**
 * CData
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string              $Content
 * @property NodeCollection      $Nodes
 */
class CData extends Base implements INode
{
    #region member
    /**
     * Content.
     *
     * @var string
     */
    private $content;
    /**
     * Nodes.
     *
     * @var NodeCollection
     */
    private $nodes;
    #endregion

    #region constructor
    /**
     * Constructor.
     *
     * @param string $content
     *
     * @return CData
     */
    public function __construct($content = '')
    {
        $this->content = $content;
        $this->nodes = new NodeCollection();
    }
    #endregion

    #region methods
    /**
     * Convert to XML.
     *
     * @return string
     */
    public function toXml()
    {
        // init
        $result = '';

        // action
        $result .= '<![CDATA['.$this->content.']]>';

        // return
        return $result;
    }
    #endregion

    #region properties
    /**
     * Content.
     *
     * @return string
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * Nodes.
     *
     * @return NodeCollection
     */
    public function getNodes()
    {
        return $this->nodes;
    }
    #endregion
}
