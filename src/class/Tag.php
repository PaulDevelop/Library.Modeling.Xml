<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Tag
 *
 * @package  Com\PaulDevelop\Library\Modeling\Xml
 * @category Modeling\Xml
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string              $Namespace
 * @property string              $Name
 * @property AttributeCollection $Attributes
 * @property NodeCollection      $Nodes
 */
class Tag extends Base implements INode, \arrayaccess
{
    #region member
    /**
     * Namespace
     *
     * @var string
     */
    private $namespace;
    /**
     * Name
     *
     * @var string
     */
    private $name;
    /**
     * Attributes.
     *
     * @var AttributeCollection
     */
    private $attributes;
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
     * @param string              $namespace
     * @param string              $name
     * @param AttributeCollection $attributes
     * @param NodeCollection      $nodes
     *
     * @return Tag
     */
    public function __construct($namespace = '', $name = '', $attributes = null, $nodes = null)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->attributes = ($attributes == null) ? new AttributeCollection() : $attributes;
        $this->nodes = ($nodes == null) ? new NodeCollection() : $nodes;
    }
    #endregion

    #region methods
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;
    }

    /**
     * Convert to XML.
     *
     * @return string
     */
    public function toXml()
    {
        // init
        $result = '';
        $tagName = ($this->namespace == '' ? '' : $this->namespace.':').$this->name;
        $attributes = '';
        foreach ($this->attributes as $attribute) {
            $attributes .= ' '.$attribute->Name.'="'.$attribute->Value.'"';
        }

        // action
        $result .= '<'.$tagName.$attributes.'>';
        foreach ($this->nodes as $node) {
            /** @var INode $node */
            $result .= $node->toXml();
        }
        $result .= '</'.$tagName.'>';

        // return
        return $result;
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName)
    {
        // init
        $result = false;

        // action
        foreach ($this->attributes as $attribute) {
            if ($attribute->Name == $attributeName) {
                $result = true;
                break;
            }
        }

        // --- result ---
        return $result;
    }

    /**
     * @param string $attributeName
     *
     * @return Attribute
     */
    public function getAttribute($attributeName)
    {
        // init
        $result = null;

        // action
        foreach ($this->attributes as $attribute) {
            if ($attribute->Name == $attributeName) {
                $result = $attribute;
                break;
            }
        }

        // --- result ---
        return $result;
    }

    /**
     * @param $path
     *
     * @return array
     */
    private function splitPath($path)
    {
        // init
        $textDelimiter = '\'';
        $pathDelimiter = '.';
        $result = array();

        // action
        $stringIsOpen = false;
        $currentChunk = '';
        for ($i = 0; $i < strlen($path); $i++) {
            $currentSymbol = $path[$i];

            if ($currentSymbol == $textDelimiter) {
                $stringIsOpen = !$stringIsOpen;
            }

            if ($currentSymbol == $pathDelimiter && !$stringIsOpen) {
                $result[count($result)] = $currentChunk;
                $currentChunk = '';
                continue;
            }
            $currentChunk .= $currentSymbol;
        }

        $result[count($result)] = $currentChunk;

        // return
        return $result;
    }

    /**
     * @param $path
     *
     * @return Tag|null
     * @throws ChildDoesNotExistException
     * @throws MultipleChildrenFoundException
     */
    public function getNode($path)
    {
        // init
        $chunks = $this->splitPath($path);

        // action
        $curObj = $this;
        foreach ($chunks as $chunk) {
            // check, if attributes exist
            $regs = array();
            preg_match('/^([a-z]+)(?:\[(.*)\])?$/i', $chunk, $regs);

            // if there are attributes, store them in array
            $chunkAttributes = array();
            if (sizeof($regs) > 2) {
                // get chunk name
                $chunk = $regs[1];

                // get attributes
                $tmpAttributes = preg_split('/\,/', $regs[2]);
                for ($i = 0; $i < sizeof($tmpAttributes); $i++) {
                    list($key, $value) = preg_split('/\=/', $tmpAttributes[$i]); // split into key = value
                    $key = substr($key, 1, strlen($key) - 1); // remove @
                    $value = trim($value, '\''); // remove ''
                    $chunkAttributes[$key] = $value; // add to attributes list
                }
            }

            if (($curObj = $curObj->getChildNode($chunk, $chunkAttributes)) != null) {
                // zuweisung schon oben in if-block schon erfolgt
            } else {
                throw new ChildDoesNotExistException('Child node "'.$chunk.'" '.' ("'.$path.'") does not exist.');
            }
        }

        // return
        return $curObj;
    }

    /**
     * @param string $nodeName
     * @param array  $nodeAttributes
     *
     * @return null
     * @throws MultipleChildrenFoundException
     */
    public function getChildNode($nodeName = "", $nodeAttributes = array())
    {
        // init
        $obj = null;
        $count = 0;

        // action
        foreach ($this->Nodes as $node) {
            if (gettype($node) == 'object'
                && get_class($node) == 'Com\PaulDevelop\Library\Modeling\Xml\Tag'
            ) {
                if ($node->Name == $nodeName) {
                    // if found child node with good name, check attributes as well
                    $allAttributesAreOK = true;
                    foreach ($nodeAttributes as $key => $value) {
                        if ($node[$key]->Value != $value) {
                            $allAttributesAreOK = false;
                            break;
                        }
                    }

                    if ($allAttributesAreOK == true) {
                        $count++;
                        if ($count > 1) {
                            throw new MultipleChildrenFoundException(
                                'Found multiple children with name "'.$nodeName.'".'
                            );
                        }
                        $obj = $node;
                    }
                }
            }
        }

        // return
        return $obj;
    }

    /**
     * @param $path
     *
     * @return TagCollection|null
     * @throws \Exception
     */
    public function getNodeCollection($path)
    {
        // init
        $result = null;
        $chunks = $this->splitPath($path);

        // action
        $curObj = $this;
        $count = 0;
        foreach ($chunks as $chunk) {
            // check, if attributes exist
            $regs = array();
            preg_match('/^([a-z]+)(?:\[(.*)\])?$/i', $chunk, $regs);

            // if there are attributes, store them in array
            $chunkAttributes = array();
            if (sizeof($regs) > 2) {
                // get chunk name
                $chunk = $regs[1];

                // get attributes
                $tmpAttributes = preg_split('/\,/', $regs[2]);
                for ($i = 0; $i < sizeof($tmpAttributes); $i++) {
                    list($key, $value) = preg_split('/\=/', $tmpAttributes[$i]); // split into key = value
                    $key = substr($key, 1, strlen($key) - 1); // remove @
                    $value = trim($value, '\''); // remove ''
                    $chunkAttributes[$key] = $value; // add to attributes list
                }
            }

            //echo $count.' / '.sizeof($chunks)."\n";

            try {
                if ($count + 1 < sizeof($chunks)) {
                    if (($curObj = $curObj->getChildNode($chunk, $chunkAttributes)) != null) {
                        // zuweisung schon oben in if-block schon erfolgt
                    } else {
                        throw new ChildDoesNotExistException(
                            'Child nodes "'.$chunk.'" '.' ("'.$path.'") does not exist.'
                        );
                    }

                } else {
                    if (sizeof($result = $curObj->getChildNodes($chunk, $chunkAttributes)) > 0) {
                        //var_dump($result);die;
                    }
                }
            } catch (\Exception $e) {
                //die('XXXXXXX');
                throw $e;
            }

            /*
            //if ( ( $curObj = $curObj->GetChildNodes($chunk, $chunkAttributes) ) != null ) {
            if ( sizeof( $curObj = $curObj->GetChildNodes($chunk, $chunkAttributes) ) > 0 ) {
              // zuweisung schon oben in if-block schon erfolgt
            }
            else {
              throw new ChildDoesNotExistException('Child nodes "'.$chunk.'" '.' ("'.$path.'") does not exist.');
            }
            */

            $count++;
        }

        // return
        return $result;
        //return $curObj;
    }

    /**
     * @param string $nodeName
     * @param array  $nodeAttributes
     *
     * @return TagCollection
     */
    public function getChildNodes($nodeName = "", $nodeAttributes = array())
    {
        // init
        $obj = new TagCollection(); // array(); // null;
        $count = 0;

        // action
        foreach ($this->Nodes as $node) {
            if (gettype($node) == 'object'
                && get_class($node) == 'Com\PaulDevelop\Library\Modeling\Xml\Tag'
            ) {
                if ($node->Name == $nodeName) {
                    // if found child node with good name, check attributes as well
                    $allAttributesAreOK = true;
                    foreach ($nodeAttributes as $key => $value) {
                        if ($node[$key]->Value != $value) {
                            $allAttributesAreOK = false;
                            break;
                        }
                    }

                    if ($allAttributesAreOK == true) {
                        $count++;
                        $obj->add($node, $count);
                    }
                }
            }
        }

        // return
        return $obj;
    }
    #endregion

    #region properties
    /**
     * Namespace.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Name.
     *
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * Attributes.
     *
     * @return AttributeCollection
     */
    protected function getAttributes()
    {
        return $this->attributes;
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
