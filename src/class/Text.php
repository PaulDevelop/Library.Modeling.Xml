<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Text
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string              $Content
 * @property NodeCollection      $Nodes
 */
class Text extends Base implements INode
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
     * @param string         $content
     * @param NodeCollection $nodes
     *
     * @return Text
     */
    public function __construct($content = '', $nodes = null)
    {
        $this->content = $content;
        $this->nodes = ($nodes == null) ? new NodeCollection() : $nodes;
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
        $result .= $this->content;

        // return
        return $result;
    }
    # endregion

    #region properties
    /**
     * Content.
     *
     * @return string
     */
    public function getContent()
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
