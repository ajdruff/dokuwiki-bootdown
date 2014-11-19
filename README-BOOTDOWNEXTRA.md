# BootDown Extra plugin for DokuWiki
    ---- plugin ----
    description: Parses Twitter Bootstrap and PHP Markdown Extra.
    author     : Andrew Druffner
    email      : andrew@nomstock.com
    type       : syntax
    lastupdate : 2014-11-05
    compatible : 2012-10-13 “Adora Belle” and newer
    depends    : 
    conflicts  :
    similar    : bootdownextra 
    tags       : formatting, markup_language
    downloadurl: 
    ----

##Download and Installation

1. Download and install the plugin using the Plugin Manager using the following URL. Refer to [[:Plugins]] on how to install plugins manually.
2. Configure Filtering
3. White List (default)  
Add namespaces or names, separated by commas, for the pages that you'll be using Markdown. Bootdown Extra will parse only those pages listed in the White List.
4. Black List 
Add namespaces or names, separated by commans, for the pages that you do *not* want parsed for Markdown. This is very useful for sites where most of your content is written in Markdown, but you want the sidebar, for example, to still be parsed for Dokuwiki tags. So, for example, if you are using a plugin in your sidebar or Dokuwiki hyperlink tags, you should add 'sidebar' (without the quotes) to the 'Black List' setting , and set Filtering to 'Black List'
5. Off
Set Filtering to to 'Off' if you want everything parsed for Markdown. Note that this can be dangerous if any of your pages or sidebars rely on plugins or Dokuwiki specific tags. When set to off, only Markdown tags will be parsed, all other Dokuwiki plugin tags will be ignored.

###Syntax Highlighting Code Blocks

Adding syntax highlighting is done by adding a code block, then following the opening fence with the class of the language you are using.


See the 'Syntax Highlighting' section in the docs for more examples of how to configure different themes for syntax highlighting.

###Twitter Bootstrap Classes

The 'Boot' in Bootdown Extra refers to Twitter Bootstrap, which is a project that .....

Bootdown Extra incorporates Twitter Bootstrap classes by importing its css classes and javascript. 

You can now do things like:











###More Examples

Filtering 

3. Filtering Option 1: 'White List'
Filtering will be set to 'White List' when you first install Bootdown Extra. This means that Markdown will only be parsed for those page names or namespaces that appear in the 'White List' setting.

Example - White List namespace 'news' and page 'my_first_post'


Example - Black List namespace 'news' 

Note that news:mystory is not parsed, and instead shows Dokuwiki specific markup




Example - Set Filtering to 'Off'
With filtering set to 'off', Bootdown Extra will parse every page for Markdown. Note that this will *break* all other Dokuwiki parsing, so if you have any plugins, they will likely not work with filtering set to off.


In the example below, our plugin '' doesn't work, so the sidebar shows unparsed tags, but its markdown tags are parsed.


Example - Black List



4. 





When you first install Bootdown Extra, it is configured by default to parse Markdown tags only for those 


###About BootDown Extra Dokuwiki Plugin


The BootDownExtra plugin allows the use of Markdown Extra + Bootstrap classes to provide easy to create web pages without having to know html.

It includes the following features:


* Markdown processing configurable by namespace
* Access to all bootstrap classes
* Beautiful Syntax Highlighting support provided by Prism and highlight.js
* Plugin support for alternative highlighters
* Use Lists without breaking syntax highlighted code blocks.

Here are some of the things you can do with BootDown: 


###About BootDown Extra 

Bootdown Extra is a fork of the PHP Markdown Extra plugin, adding Twitter Bootstrap classes, and code syntax highlighing using the Prism and  Highlightjs projects, and adds support code block support for lists.


Prism Supported Languages:

* HTML
* CSS
* Javascript
* PHP
* BASH
* + more by customizing your own  [Prism](http://prismjs.com/download.html)

Available Themes:

* Default
* Dark'
* Funky
* Okaidia
* Twighlight
* Coy

Highlightjs Supported Languages:

* CSS
* Javqascript
* Bash
* PHP
* JSON
* Markdown
* HTML,XML
* + more by customizing your own [highlightjs](https://highlightjs.org/download/)


###Usage

1. Configure 

When Bootdown Extra is first installed, it won't 




The use of Bootdown Extra will pr

Bootdown Extra is designed by default to rely nearly exclusively on Markdown
for its parsing. It assumes that most of your content is done
using Markdown tags and that Dokuwiki is used primarily for hosting your 
content. 

Because of this approach, and unlike the PHP Markdown Extra plugin, Bootdown Extra allows you to use Markdown and Twitter Bootstrap classes without having to add a '.md' extension to 
the markdown file.


Instead, Bootdown Extra parses all files for Markdown tags unless the file or 
its namespace is specifically added to a 'no_markdown' setting in the plugin's




