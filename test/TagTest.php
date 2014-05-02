<?php

namespace Com\PaulDevelop\Library\Modeling\Xml;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testTagToXml()
    {
        $attributes = new AttributeCollection();
        $attributes->add(new Attribute('version', '1.0.0'));
        $nodes = new NodeCollection();
        $nodes->add(new CData('<foo>&amp;</foo>'));
        $nodes->add(new Comment('Commentary block'));
        $nodes->add(new Text('I am a template tag.'));
        $layoutNodes = new NodeCollection();
        $setAttributes = new AttributeCollection();
        $setAttributes->add(new Attribute('name', 'foo'));
        $setAttributes->add(new Attribute('value', 'bar'));
        $layoutNodes->add(new Tag('pd', 'set', $setAttributes, new NodeCollection()));
        $nodes->add(new Tag('pd', 'layout', new AttributeCollection(), $layoutNodes));

        $tag = new Tag(
            'pd',
            'template',
            $attributes,
            $nodes
        );

        $this->assertEquals(
            '<pd:template version="1.0.0"><![CDATA[<foo>&amp;</foo>]]><!--Commentary block-->I am a template tag.<pd:layout><pd:set name="foo" value="bar"></pd:set></pd:layout></pd:template>',
            $tag->toXml()
        );
    }

    /**
     * @test
     */
    public function testTagGetChildNodes()
    {
        $attributes = new AttributeCollection();
        $attributes->add(new Attribute('version', '1.0.0'));
        $nodes = new NodeCollection();
        $nodes->add(new CData('<foo>&amp;</foo>'));
        $nodes->add(new Comment('Commentary block'));
        $nodes->add(new Text('I am a template tag.'));
        $layoutNodes = new NodeCollection();
        $setAttributes = new AttributeCollection();
        $setAttributes->add(new Attribute('name', 'foo'));
        $setAttributes->add(new Attribute('value', 'bar'));
        $setTag = new Tag('pd', 'set', $setAttributes, new NodeCollection());
        $layoutNodes->add($setTag);
        $nodes->add(new Tag('pd', 'layout', new AttributeCollection(), $layoutNodes));

        $tag = new Tag(
            'pd',
            'template',
            $attributes,
            $nodes
        );

        $this->assertEquals($setTag, $tag->getNode('layout.set'));
    }
}
