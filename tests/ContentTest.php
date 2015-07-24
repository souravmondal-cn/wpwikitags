<?php

use \wpWikiTags\Content;

class ContentTest extends \PHPUnit_Framework_TestCase {

    /**
     * Check the Content parser is working properly or not
     */
    public function testContentParsing() {

        require_once __DIR__ . '/../../../../wp-load.php';

        $content = file_get_contents(__DIR__ . '/testResources/content.html');
        $filterKeywords = array(
            'whiteList' => array('pagemaker'),
            'blackList' => array('desktop')
        );
        $filterMode = 'white_list';
        $contentParser = new Content();
        echo $parsedContent = $contentParser->convertContent($content, $filterKeywords, $filterMode);
    }

}
