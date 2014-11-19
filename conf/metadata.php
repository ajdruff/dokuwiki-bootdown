<?php
/**
 * Metadata for configuration manager plugin
 * Additions for the Bootdown Extra plugin
 *
 * @author    Andrew Druffner <andrew@nomstock.com>
 */
 


$meta['frontmatter'] = array('onoff');
$meta['whitelist'] = array('');
$meta['blacklist'] = array('');
$meta['filtering'] = array('multichoice','_choices'=>array('White List','Black List','Off'));
$meta['markdownparser'] = array('multichoice','_choices'=>array('PHP Markdown Extra Classic','Parsedown','Parsedown Extra'));

            