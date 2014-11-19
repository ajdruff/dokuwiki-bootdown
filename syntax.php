<?php

/**
 * Bootdown Extra plugin for DokuWiki.
 *
 * @license GPL 3 (http://www.gnu.org/licenses/gpl.html) - NOTE: PHP Markdown
 * Extra is licensed under the BSD license. See License.text for details.
 * @version 1.03 - 24.11.2012 - PHP Markdown Extra 1.2.8 included.
 * @author Andrew Druffner <andrew@nomstock.com>, @author Joonas Pulakka <joonas.pulakka@iki.fi>, Jiang Le <smartynaoki@gmail.com>
 */
if ( !defined( 'DOKU_INC' ) )
    die();
if ( !defined( 'DOKU_PLUGIN' ) )
    define( 'DOKU_PLUGIN', DOKU_INC . 'lib/plugins/' );
require_once (DOKU_PLUGIN . 'syntax.php');

//require_once (DOKU_PLUGIN . 'bootdownextra/markdown.php');
//require_once (DOKU_PLUGIN . 'bootdownextra/parsedown/Parsedown.php');
// require_once (DOKU_PLUGIN . 'bootdownextra/parsedown/Parsedown.php');
class syntax_plugin_bootdownextra extends DokuWiki_Syntax_Plugin {

    private $_parser = null;

    /**
     * Get Markdown Parser
     *
     * Get Markdown Parser
     *
     * @param none
     * @return void
     */
    public function getMarkdownParser() {
        if (is_null($this->_parser)){
                //replace with setting lookup.
        //$this->_parser = 'PHP Markdown Extra Classic';
        //$this->_parser='Parsedown';
        //$this->_parser='Parsedown Extra';
$this->_parser = $this->getConf( 'markdownparser' );
        }
            return $this->_parser;

    }
    function __construct() {

        switch ( $this->getMarkdownParser() ){

            case 'PHP Markdown Extra Classic':

                require_once (DOKU_PLUGIN . 'bootdownextra/phpmarkdown-extra-classic/markdown.php');
                break;
            case 'Parsedown':
                require_once (DOKU_PLUGIN . 'bootdownextra/parsedown/Parsedown.php');
                break;
            case 'Parsedown Extra':
                require_once (DOKU_PLUGIN . 'bootdownextra/parsedown/Parsedown.php');
                require_once (DOKU_PLUGIN . 'bootdownextra/parsedown-extra/ParsedownExtra.php');
                break;
            default://Parsdown Extra as default
                require_once (DOKU_PLUGIN . 'bootdownextra/parsedown/Parsedown.php');
               require_once (DOKU_PLUGIN . 'bootdownextra/parsedown-extra/ParsedownExtra.php');
                break;

       }


    }

    /**
     * Parse Markdown
     *
     * Parses the Markdown and returns a rendered string
     *
     * @param $markdown The text containing Markdown
     * @return string $html The rendered HTML
     */
    private function _parseMarkdown( $markdown ){

        switch ( $this->_parser ){

            case 'PHP Markdown Extra Classic':

                $html = Markdown( $markdown );
                break;
            case 'Parsedown':
                $Parsedown = new Parsedown();

                $html = $Parsedown->text( $markdown );

                break;

            case 'Parsedown Extra':
                $Parsedown = new ParsedownExtra();

                $html = $Parsedown->text( $markdown );

                break;
            default://Parsedown Extra as default
                $Parsedown = new ParsedownExtra();

                $html = $Parsedown->text( $markdown );

                break;

       }

        return $html;

        }

    function getType() {
        return 'protected'; //protected only markdown is applied. container others applied too
    }

    function getPType() {
        return 'block';
    }

    function getSort() {
        return 69;
    }

    function connectTo( $mode ) {
      $this->Lexer->addEntryPattern( '<markdown>(?=.*</markdown>)', $mode, 'plugin_bootdownextra' );
        //orig, works $this->Lexer->addEntryPattern( '<markdown>(?=.*</markdown>)', $mode, 'plugin_bootdownextra' );
        
    }

    function postConnect() {
        $this->Lexer->addExitPattern( '</markdown>', 'plugin_bootdownextra' );
    }

    function handle( $match, $state, $pos, &$handler ) {
        switch ( $state ) {
            case DOKU_LEXER_ENTER : return array( $state, '' );
            case DOKU_LEXER_UNMATCHED : return array( $state, $this->_parseMarkdown( $match ) );
            case DOKU_LEXER_EXIT : return array( $state, '' );
        }
        return array( $state, '' );
    }

    function render( $mode, &$renderer, $data ) {
        //dbg('function render($mode, &$renderer, $data)-->'.' mode = '.$mode.' data = '.$data);
        //dbg($data);
        if ( $mode == 'xhtml' ) {
            list($state, $match) = $data;
            switch ( $state ) {
                case DOKU_LEXER_ENTER : break;
                case DOKU_LEXER_UNMATCHED :
                    $match = $this->_toc( $renderer, $match );
                    $renderer->doc .= $match;
                    break;
                case DOKU_LEXER_EXIT : break;
            }
            return true;
        } else if ( $mode == 'metadata' ) {
            //dbg('function render($mode, &$renderer, $data)-->'.' mode = '.$mode.' data = '.$data);
            //dbg($data);
            list($state, $match) = $data;
            switch ( $state ) {
                case DOKU_LEXER_ENTER : break;
                case DOKU_LEXER_UNMATCHED :
                    if ( !$renderer->meta[ 'title' ] ){
                        $renderer->meta[ 'title' ] = $this->_markdown_header( $match );
                    }
                    $this->_toc( $renderer, $match );
                    $internallinks = $this->_internallinks( $match );
                    #dbg($internallinks);
                    if ( count( $internallinks ) > 0 ){
                        foreach ( $internallinks as $internallink )
                        {
                            $renderer->internallink( $internallink );
                        }
                    }
                    break;
                case DOKU_LEXER_EXIT : break;
            }
            return true;
        } else {
            return false;
        }
    }

    function _markdown_header( $text )
    {
        $doc = new DOMDocument( '1.0', 'UTF-8' );
        //dbg($doc);
        $meta = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
        $doc->loadHTML( $meta . $text );
        //dbg($doc->saveHTML());
        if ( $nodes = $doc->getElementsByTagName( 'h1' ) ){
            return $nodes->item( 0 )->nodeValue;
        }
        return false;
    }

    function _internallinks( $text )
    {

        $doc = new DOMDocument( '1.0', 'UTF-8' );
        @$doc->loadHTML( $text ); //suppress warning with @ for empty documents
        $links = array();
        if ( $nodes = $doc->getElementsByTagName( 'a' ) ){
            foreach ( $nodes as $atag )
            {
                $href = $atag->getAttribute( 'href' );
                if ( !preg_match( '/^(https{0,1}:\/\/|ftp:\/\/|mailto:)/i', $href ) ){
                    $links[] = $href;
                }
            }
        }
        return $links;
    }

    function _toc( &$renderer, $text )
    {
        $doc = new DOMDocument( '1.0', 'UTF-8' );
        //dbg($doc);
        $meta = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
        $doc->loadHTML( $meta . $text );
        if ( $nodes = $doc->getElementsByTagName( "*" ) ){
            foreach ( $nodes as $node )
            {
                if ( preg_match( '/h([1-7])/', $node->tagName, $match ) )
                {
                    #dbg($node);
                    $node->setAttribute( 'class', 'sectionedit' . $match[ 1 ] );
                    $hid = $renderer->_headerToLink( $node->nodeValue, 'true' );
                    $node->setAttribute( 'id', $hid );
                    $renderer->toc_additem( $hid, $node->nodeValue, $match[ 1 ] );
                }

            }
        }
        //remove outer tags of content
        $html = $doc->saveHTML();
        $html = str_replace( '<!DOCTYPE html>', '', $html );
        $html = preg_replace( '/.+<body>/', '', $html );
        $html = str_replace( '</body>', '', $html );
        return $html;
    }

}
