<?php

/**
 * DokuWiki Plugin bootdownextra (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andrew Druffner <andrew@nomstock.com>
 * @author  Andreas Gohr <andi@splitbrain.org>

 */
// must be run within Dokuwiki
if ( !defined( 'DOKU_INC' ) )
    die();
if ( !defined( 'DOKU_PLUGIN' ) )
    define( 'DOKU_PLUGIN', DOKU_INC . 'lib/plugins/' );
require_once DOKU_PLUGIN . 'action.php';

class action_plugin_bootdownextra extends DokuWiki_Action_Plugin {

    function register( &$controller ) {
        $controller->register_hook( 'PARSER_WIKITEXT_PREPROCESS', 'BEFORE', $this, 'handle_parser_wikitext_preprocess' );
    }

    function handle_parser_wikitext_preprocess( &$event, $param ) {


        global $ID;
        $INFO = pageinfo(); //use pageinfo() instead of global $INFO since $INFO doesnt always get populated when we need it.
        $namespace = $INFO[ 'namespace' ];
        $pageid = $ID;


        //echo '<br>parsed by action.php, parsing markdown and id = ' . $pageid . ' and namespace = ' . $namespace;

        /*
         * Return if namespace or page shouldn't be parsed for markdown
         */

        if ( !$this->_should_apply_markdown( $namespace, $pageid ) ){

            return true;
                   }
/*
 * Add special opening and closing tags to allow Bootstrap to be recognized whenever
 * Extended Markup is employed. This enables markdown within html, and adds
 * a namspace class for the bootstrap css
 * 
 *  use $this_plugin = plugin_load( 'syntax', 'bootdownextra' );$this_plugin->getMardownPaser();
  or  $this->getConf( 'markdownparser' ); to get the name of the parser.
 * 
 */


        $bootstrap_opening_tag = '';
        $bootstrap_closing_tag = '';
$bootstrap_parsedown_opening_tag= '';
        $bootstrap_parsedown_closing_tag= '';
        switch ( $this->getConf( 'markdownparser' ) ){

            case 'PHP Markdown Extra Classic':

               $bootstrap_opening_tag = "<markdown><div class='bootdown' markdown='1'>";
                $bootstrap_closing_tag = '</div></markdown>';
                
                                $bootstrap_opening_tag = "<markdown>";
                $bootstrap_closing_tag = '</markdown>';
                
                break;
            case 'Parsedown':
                //no need for bootdown class since classes not supported
                $bootstrap_opening_tag = '';
                $bootstrap_closing_tag = '';

                break;
            case 'Parsedown Extra':
                $bootstrap_opening_tag = "<markdown>";
                $bootstrap_closing_tag = '</markdown>';
                break;
            default://Parsdown Extra as default
                $bootstrap_opening_tag = "<div class='bootdown' markdown='1'>";
                $bootstrap_closing_tag = '</div>';
                break;

       }




        if ( $this->getConf( 'frontmatter' ) ){
            if ( preg_match( '/^---\s*\n(.*?\n?)^---\s*$\n?(.+)/sm', $event->data, $match ) ){
                $event->data = sprintf( "%s<markdown>$bootstrap_opening_tag\n%s\n$bootstrap_closing_tag</markdown>", $match[ 1 ], $match[ 2 ] );
           } else{
                $event->data = "$bootstrap_opening_tag\n" . $event->data . "\n$bootstrap_closing_tag";

           }
       } else{
                           $event->data = "$bootstrap_opening_tag\n" . $event->data . "\n$bootstrap_closing_tag";

       }

    }

    private function _should_apply_markdown( $namespace, $pagename ){

        /*

         * If filtering is off, parses everything as markdown,ignores dokuwiki markup
         * If filtering is 'White List' , parses only those namespaces and page names on White List
         * If filtering is 'Black List', parses everything Except those namespaces and page names on Black List
         *  */


        $whitelist = explode( ',', $this->getConf( 'whitelist' ) );
        $blacklist = explode( ',', $this->getConf( 'blacklist' ) );
        $filtering = $this->getConf( 'filtering' );





        switch ( $filtering ){

            case 'White List':

                $result = $this->_isWhiteListed( $pagename, $namespace, $whitelist );

                break;
            case 'Black List':

                $result = !$this->_isBlackListed( $pagename, $namespace, $blacklist );

                break;

            case 'Off':

                $result = true; //if filtering is off, parse everything 
                break;
            default:
                $result = $this->_isWhiteListed( $pagename, $namespace, $whitelist );

                break;

       }



        return $result;



            }

    /**
     * Is White Listed
     *
     * Returns true if pagename or namespace is in white list, otherwise false
     *
     * @param $pagename string The name of the page
     * @param $namespace string The namespace of the page
     * @param $list array An array of strings
     * @return boolean
     */
    private function _isWhiteListed( $pagename, $namespace, $list ) {

        $result = false;
        if ( in_array( $pagename, $list ) ){ $result = true; };
        if ( in_array( $namespace, $list ) ){ $result = true; };
        return $result;
    }

    /**
     * Is Black Listed
     *
     * Returns true if pagename or namespace is in black list, otherwise false
     *
     * @param $pagename string The name of the page
     * @param $namespace string The namespace of the page
     * @param $list array An array of strings
     * @return boolean
     */
    private function _isBlackListed( $pagename, $namespace, $list ) {

        $result = false;
        if ( in_array( $pagename, $list ) ){ $result = true; };
        if ( in_array( $namespace, $list ) ){ $result = true; };
        return $result;

    }
}
