<?php

########################################################################
# Extension Manager/Repository config file for ext "tinymce_rte".
#
# Auto generated 16-11-2011 17:32
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'TinyMCE RTE',
	'description' => 'An advanced integration of TinyMCE which supports all TinyMCE plugins and languages.',
	'category' => 'be',
	'shy' => 0,
	'version' => '0.8.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod2,mod3,mod4',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/tinymce_rte/',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'webteam.at',
	'author_email' => 'typo3@webteam.at',
	'author_company' => 'WEBTEAM GMBH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.2.0-0.0.0',
		),
		'conflicts' => array(
			'ch_rterecords' => '',
			'linkhandler' => '',
			'rtehtmlarea' => '',
			'rte_tinymce' => '',
			'tinyrteru' => '',
			'tinyrte' => '',
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:415:{s:20:"class.ext_update.php";s:4:"ec98";s:29:"class.tx_tinymce_rte_base.php";s:4:"1629";s:33:"class.ux_t3lib_parsehtml_proc.php";s:4:"89d8";s:21:"ext_conf_template.txt";s:4:"b2ca";s:12:"ext_icon.gif";s:4:"0e8d";s:17:"ext_localconf.php";s:4:"821a";s:14:"ext_tables.php";s:4:"7b34";s:12:"ux_index.php";s:4:"1ae2";s:14:"doc/manual.sxw";s:4:"9d07";s:40:"hooks/class.tx_tinymce_rte_feeditadv.php";s:4:"b5d4";s:38:"hooks/class.tx_tinymce_rte_handler.php";s:4:"7e23";s:37:"hooks/class.tx_tinymce_rte_header.php";s:4:"9d48";s:21:"mod1/browse_links.php";s:4:"e84e";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"de9c";s:21:"mod1/include_php4.inc";s:4:"21d5";s:21:"mod1/include_php5.inc";s:4:"8ba2";s:31:"mod1/locallang_browse_links.xml";s:4:"291f";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"a4fa";s:35:"mod2/locallang_rte_select_image.xml";s:4:"55bb";s:25:"mod2/rte_select_image.php";s:4:"694e";s:14:"mod3/changelog";s:4:"8b05";s:13:"mod3/conf.php";s:4:"8f86";s:15:"mod3/config.php";s:4:"3947";s:12:"mod3/rpc.php";s:4:"a926";s:29:"mod3/classes/EnchantSpell.php";s:4:"84be";s:28:"mod3/classes/GoogleSpell.php";s:4:"6098";s:23:"mod3/classes/PSpell.php";s:4:"65a0";s:28:"mod3/classes/PSpellShell.php";s:4:"38bb";s:29:"mod3/classes/SpellChecker.php";s:4:"d708";s:27:"mod3/classes/utils/JSON.php";s:4:"45c8";s:29:"mod3/classes/utils/Logger.php";s:4:"317d";s:25:"mod3/includes/general.php";s:4:"4573";s:24:"mod4/TinyMCETemplate.php";s:4:"c120";s:39:"mod4/class.tx_tinymce_rte_templates.php";s:4:"edc9";s:13:"mod4/conf.php";s:4:"36a4";s:28:"patcher/class.pmkpatcher.php";s:4:"a589";s:43:"patcher/diffs/advlink.enablepopuplinks.diff";s:4:"f551";s:35:"patcher/diffs/typo3filemanager.diff";s:4:"b7e4";s:45:"patcher/diffs/typo3filemanager/typo3image.gif";s:4:"bf3e";s:44:"patcher/diffs/typo3filemanager/typo3link.gif";s:4:"13c2";s:43:"patcher/diffs2/advlink.hideadvancedtab.diff";s:4:"b9ee";s:41:"patcher/diffs2/advlink.hideanchorbox.diff";s:4:"672c";s:40:"patcher/diffs2/advlink.hideclassbox.diff";s:4:"bc4a";s:40:"patcher/diffs2/advlink.hidepopuptab.diff";s:4:"436d";s:37:"patcher/diffs2/advlink.shadowbox.diff";s:4:"00a1";s:30:"patcher/unused/fullscreen.diff";s:4:"bdf5";s:32:"pi1/class.tx_tinymce_rte_pi1.php";s:4:"ea70";s:30:"res/tiny_mce/jquery.tinymce.js";s:4:"e536";s:24:"res/tiny_mce/license.txt";s:4:"0571";s:24:"res/tiny_mce/tiny_mce.js";s:4:"72f3";s:28:"res/tiny_mce/tiny_mce_dev.js";s:4:"9585";s:29:"res/tiny_mce/tiny_mce_gzip.js";s:4:"75d8";s:30:"res/tiny_mce/tiny_mce_gzip.php";s:4:"55bf";s:31:"res/tiny_mce/tiny_mce_jquery.js";s:4:"52aa";s:35:"res/tiny_mce/tiny_mce_jquery_src.js";s:4:"569d";s:30:"res/tiny_mce/tiny_mce_popup.js";s:4:"9a9c";s:34:"res/tiny_mce/tiny_mce_prototype.js";s:4:"840d";s:38:"res/tiny_mce/tiny_mce_prototype_src.js";s:4:"aa2c";s:28:"res/tiny_mce/tiny_mce_src.js";s:4:"c6d1";s:36:"res/tiny_mce/classes/AddOnManager.js";s:4:"2364";s:38:"res/tiny_mce/classes/ControlManager.js";s:4:"8bf5";s:30:"res/tiny_mce/classes/Editor.js";s:4:"5ab5";s:38:"res/tiny_mce/classes/EditorCommands.js";s:4:"7598";s:37:"res/tiny_mce/classes/EditorManager.js";s:4:"ec04";s:35:"res/tiny_mce/classes/ForceBlocks.js";s:4:"f8e2";s:33:"res/tiny_mce/classes/Formatter.js";s:4:"1c22";s:35:"res/tiny_mce/classes/LegacyInput.js";s:4:"4365";s:29:"res/tiny_mce/classes/Popup.js";s:4:"41cf";s:35:"res/tiny_mce/classes/UndoManager.js";s:4:"7566";s:37:"res/tiny_mce/classes/WindowManager.js";s:4:"5c98";s:31:"res/tiny_mce/classes/tinymce.js";s:4:"feef";s:46:"res/tiny_mce/classes/adapter/jquery/adapter.js";s:4:"701a";s:53:"res/tiny_mce/classes/adapter/jquery/jquery.tinymce.js";s:4:"430d";s:49:"res/tiny_mce/classes/adapter/prototype/adapter.js";s:4:"1dca";s:36:"res/tiny_mce/classes/dom/DOMUtils.js";s:4:"5358";s:35:"res/tiny_mce/classes/dom/Element.js";s:4:"7a06";s:38:"res/tiny_mce/classes/dom/EventUtils.js";s:4:"a722";s:33:"res/tiny_mce/classes/dom/Range.js";s:4:"42e1";s:38:"res/tiny_mce/classes/dom/RangeUtils.js";s:4:"68ec";s:40:"res/tiny_mce/classes/dom/ScriptLoader.js";s:4:"acce";s:37:"res/tiny_mce/classes/dom/Selection.js";s:4:"e46e";s:38:"res/tiny_mce/classes/dom/Serializer.js";s:4:"61ad";s:34:"res/tiny_mce/classes/dom/Sizzle.js";s:4:"4353";s:38:"res/tiny_mce/classes/dom/TreeWalker.js";s:4:"863c";s:44:"res/tiny_mce/classes/dom/TridentSelection.js";s:4:"473b";s:44:"res/tiny_mce/classes/firebug/FIREBUG.LICENSE";s:4:"3b40";s:44:"res/tiny_mce/classes/firebug/firebug-lite.js";s:4:"1c9c";s:38:"res/tiny_mce/classes/html/DomParser.js";s:4:"299c";s:37:"res/tiny_mce/classes/html/Entities.js";s:4:"f22d";s:33:"res/tiny_mce/classes/html/Node.js";s:4:"51e5";s:38:"res/tiny_mce/classes/html/SaxParser.js";s:4:"b099";s:35:"res/tiny_mce/classes/html/Schema.js";s:4:"9d18";s:39:"res/tiny_mce/classes/html/Serializer.js";s:4:"6e38";s:35:"res/tiny_mce/classes/html/Styles.js";s:4:"eba9";s:35:"res/tiny_mce/classes/html/Writer.js";s:4:"1dee";s:33:"res/tiny_mce/classes/ui/Button.js";s:4:"2cd5";s:43:"res/tiny_mce/classes/ui/ColorSplitButton.js";s:4:"5a67";s:36:"res/tiny_mce/classes/ui/Container.js";s:4:"4148";s:34:"res/tiny_mce/classes/ui/Control.js";s:4:"ff71";s:35:"res/tiny_mce/classes/ui/DropMenu.js";s:4:"6e9e";s:45:"res/tiny_mce/classes/ui/KeyboardNavigation.js";s:4:"a1dd";s:34:"res/tiny_mce/classes/ui/ListBox.js";s:4:"df15";s:31:"res/tiny_mce/classes/ui/Menu.js";s:4:"72f7";s:37:"res/tiny_mce/classes/ui/MenuButton.js";s:4:"80a1";s:35:"res/tiny_mce/classes/ui/MenuItem.js";s:4:"d13d";s:40:"res/tiny_mce/classes/ui/NativeListBox.js";s:4:"28d9";s:36:"res/tiny_mce/classes/ui/Separator.js";s:4:"2bf2";s:38:"res/tiny_mce/classes/ui/SplitButton.js";s:4:"64ec";s:34:"res/tiny_mce/classes/ui/Toolbar.js";s:4:"b1d6";s:39:"res/tiny_mce/classes/ui/ToolbarGroup.js";s:4:"ab2a";s:35:"res/tiny_mce/classes/util/Cookie.js";s:4:"e040";s:39:"res/tiny_mce/classes/util/Dispatcher.js";s:4:"1c3b";s:33:"res/tiny_mce/classes/util/JSON.js";s:4:"6bd7";s:34:"res/tiny_mce/classes/util/JSONP.js";s:4:"a0c7";s:40:"res/tiny_mce/classes/util/JSONRequest.js";s:4:"5aad";s:35:"res/tiny_mce/classes/util/Quirks.js";s:4:"994c";s:32:"res/tiny_mce/classes/util/URI.js";s:4:"494f";s:31:"res/tiny_mce/classes/util/VK.js";s:4:"1edd";s:32:"res/tiny_mce/classes/util/XHR.js";s:4:"8c57";s:24:"res/tiny_mce/langs/de.js";s:4:"028f";s:24:"res/tiny_mce/langs/en.js";s:4:"6128";s:43:"res/tiny_mce/plugins/advhr/editor_plugin.js";s:4:"d0a0";s:47:"res/tiny_mce/plugins/advhr/editor_plugin_src.js";s:4:"a7fd";s:35:"res/tiny_mce/plugins/advhr/rule.htm";s:4:"492e";s:40:"res/tiny_mce/plugins/advhr/css/advhr.css";s:4:"15df";s:37:"res/tiny_mce/plugins/advhr/js/rule.js";s:4:"ef46";s:42:"res/tiny_mce/plugins/advhr/langs/de_dlg.js";s:4:"f620";s:42:"res/tiny_mce/plugins/advhr/langs/en_dlg.js";s:4:"af62";s:46:"res/tiny_mce/plugins/advimage/editor_plugin.js";s:4:"8af1";s:50:"res/tiny_mce/plugins/advimage/editor_plugin_src.js";s:4:"958a";s:39:"res/tiny_mce/plugins/advimage/image.htm";s:4:"0d20";s:46:"res/tiny_mce/plugins/advimage/css/advimage.css";s:4:"1ccd";s:44:"res/tiny_mce/plugins/advimage/img/sample.gif";s:4:"b9c7";s:41:"res/tiny_mce/plugins/advimage/js/image.js";s:4:"87dd";s:45:"res/tiny_mce/plugins/advimage/langs/de_dlg.js";s:4:"1593";s:45:"res/tiny_mce/plugins/advimage/langs/en_dlg.js";s:4:"6f80";s:45:"res/tiny_mce/plugins/advlink/editor_plugin.js";s:4:"5e44";s:49:"res/tiny_mce/plugins/advlink/editor_plugin_src.js";s:4:"4104";s:37:"res/tiny_mce/plugins/advlink/link.htm";s:4:"206e";s:44:"res/tiny_mce/plugins/advlink/css/advlink.css";s:4:"aaf2";s:42:"res/tiny_mce/plugins/advlink/js/advlink.js";s:4:"15a7";s:44:"res/tiny_mce/plugins/advlink/langs/de_dlg.js";s:4:"2df2";s:44:"res/tiny_mce/plugins/advlink/langs/en_dlg.js";s:4:"8da3";s:45:"res/tiny_mce/plugins/advlist/editor_plugin.js";s:4:"5f1c";s:49:"res/tiny_mce/plugins/advlist/editor_plugin_src.js";s:4:"d451";s:46:"res/tiny_mce/plugins/autolink/editor_plugin.js";s:4:"5875";s:50:"res/tiny_mce/plugins/autolink/editor_plugin_src.js";s:4:"7a93";s:48:"res/tiny_mce/plugins/autoresize/editor_plugin.js";s:4:"ed6f";s:52:"res/tiny_mce/plugins/autoresize/editor_plugin_src.js";s:4:"0504";s:46:"res/tiny_mce/plugins/autosave/editor_plugin.js";s:4:"b507";s:50:"res/tiny_mce/plugins/autosave/editor_plugin_src.js";s:4:"12f0";s:41:"res/tiny_mce/plugins/autosave/langs/en.js";s:4:"a33b";s:44:"res/tiny_mce/plugins/bbcode/editor_plugin.js";s:4:"3174";s:48:"res/tiny_mce/plugins/bbcode/editor_plugin_src.js";s:4:"8424";s:49:"res/tiny_mce/plugins/contextmenu/editor_plugin.js";s:4:"80e3";s:53:"res/tiny_mce/plugins/contextmenu/editor_plugin_src.js";s:4:"3c35";s:52:"res/tiny_mce/plugins/directionality/editor_plugin.js";s:4:"653c";s:56:"res/tiny_mce/plugins/directionality/editor_plugin_src.js";s:4:"f55b";s:46:"res/tiny_mce/plugins/emotions/editor_plugin.js";s:4:"98cb";s:50:"res/tiny_mce/plugins/emotions/editor_plugin_src.js";s:4:"4cbc";s:42:"res/tiny_mce/plugins/emotions/emotions.htm";s:4:"ab02";s:49:"res/tiny_mce/plugins/emotions/img/smiley-cool.gif";s:4:"e26e";s:48:"res/tiny_mce/plugins/emotions/img/smiley-cry.gif";s:4:"e72b";s:55:"res/tiny_mce/plugins/emotions/img/smiley-embarassed.gif";s:4:"d591";s:58:"res/tiny_mce/plugins/emotions/img/smiley-foot-in-mouth.gif";s:4:"c12d";s:50:"res/tiny_mce/plugins/emotions/img/smiley-frown.gif";s:4:"5993";s:53:"res/tiny_mce/plugins/emotions/img/smiley-innocent.gif";s:4:"ec04";s:49:"res/tiny_mce/plugins/emotions/img/smiley-kiss.gif";s:4:"4ae8";s:53:"res/tiny_mce/plugins/emotions/img/smiley-laughing.gif";s:4:"c37f";s:56:"res/tiny_mce/plugins/emotions/img/smiley-money-mouth.gif";s:4:"11c1";s:51:"res/tiny_mce/plugins/emotions/img/smiley-sealed.gif";s:4:"bb82";s:50:"res/tiny_mce/plugins/emotions/img/smiley-smile.gif";s:4:"2968";s:54:"res/tiny_mce/plugins/emotions/img/smiley-surprised.gif";s:4:"2e13";s:55:"res/tiny_mce/plugins/emotions/img/smiley-tongue-out.gif";s:4:"5ec3";s:54:"res/tiny_mce/plugins/emotions/img/smiley-undecided.gif";s:4:"3c0c";s:49:"res/tiny_mce/plugins/emotions/img/smiley-wink.gif";s:4:"8972";s:49:"res/tiny_mce/plugins/emotions/img/smiley-yell.gif";s:4:"19bb";s:44:"res/tiny_mce/plugins/emotions/js/emotions.js";s:4:"85ef";s:45:"res/tiny_mce/plugins/emotions/langs/de_dlg.js";s:4:"9733";s:45:"res/tiny_mce/plugins/emotions/langs/en_dlg.js";s:4:"62c0";s:39:"res/tiny_mce/plugins/example/dialog.htm";s:4:"e617";s:45:"res/tiny_mce/plugins/example/editor_plugin.js";s:4:"e0a1";s:49:"res/tiny_mce/plugins/example/editor_plugin_src.js";s:4:"3fcf";s:44:"res/tiny_mce/plugins/example/img/example.gif";s:4:"6036";s:41:"res/tiny_mce/plugins/example/js/dialog.js";s:4:"8324";s:40:"res/tiny_mce/plugins/example/langs/en.js";s:4:"78c8";s:44:"res/tiny_mce/plugins/example/langs/en_dlg.js";s:4:"7aec";s:56:"res/tiny_mce/plugins/example_dependency/editor_plugin.js";s:4:"405d";s:60:"res/tiny_mce/plugins/example_dependency/editor_plugin_src.js";s:4:"4738";s:46:"res/tiny_mce/plugins/fullpage/editor_plugin.js";s:4:"5dfb";s:50:"res/tiny_mce/plugins/fullpage/editor_plugin_src.js";s:4:"636a";s:42:"res/tiny_mce/plugins/fullpage/fullpage.htm";s:4:"8c58";s:46:"res/tiny_mce/plugins/fullpage/css/fullpage.css";s:4:"2ac6";s:44:"res/tiny_mce/plugins/fullpage/js/fullpage.js";s:4:"a791";s:45:"res/tiny_mce/plugins/fullpage/langs/de_dlg.js";s:4:"138d";s:45:"res/tiny_mce/plugins/fullpage/langs/en_dlg.js";s:4:"963f";s:48:"res/tiny_mce/plugins/fullscreen/editor_plugin.js";s:4:"38b4";s:52:"res/tiny_mce/plugins/fullscreen/editor_plugin_src.js";s:4:"0607";s:46:"res/tiny_mce/plugins/fullscreen/fullscreen.htm";s:4:"cbca";s:45:"res/tiny_mce/plugins/iespell/editor_plugin.js";s:4:"2252";s:49:"res/tiny_mce/plugins/iespell/editor_plugin_src.js";s:4:"311e";s:50:"res/tiny_mce/plugins/inlinepopups/editor_plugin.js";s:4:"cbfe";s:54:"res/tiny_mce/plugins/inlinepopups/editor_plugin_src.js";s:4:"4b1b";s:46:"res/tiny_mce/plugins/inlinepopups/template.htm";s:4:"3d7e";s:62:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/window.css";s:4:"f715";s:65:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/alert.gif";s:4:"568d";s:66:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/button.gif";s:4:"19f8";s:67:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/buttons.gif";s:4:"1743";s:67:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/confirm.gif";s:4:"1bc3";s:67:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/corners.gif";s:4:"5529";s:70:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/horizontal.gif";s:4:"0365";s:68:"res/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/vertical.gif";s:4:"0261";s:52:"res/tiny_mce/plugins/insertdatetime/editor_plugin.js";s:4:"d990";s:56:"res/tiny_mce/plugins/insertdatetime/editor_plugin_src.js";s:4:"32a2";s:43:"res/tiny_mce/plugins/layer/editor_plugin.js";s:4:"4e5f";s:47:"res/tiny_mce/plugins/layer/editor_plugin_src.js";s:4:"3754";s:50:"res/tiny_mce/plugins/legacyoutput/editor_plugin.js";s:4:"3e3a";s:54:"res/tiny_mce/plugins/legacyoutput/editor_plugin_src.js";s:4:"b23b";s:43:"res/tiny_mce/plugins/lists/editor_plugin.js";s:4:"891a";s:47:"res/tiny_mce/plugins/lists/editor_plugin_src.js";s:4:"2ca0";s:43:"res/tiny_mce/plugins/media/editor_plugin.js";s:4:"9ce4";s:47:"res/tiny_mce/plugins/media/editor_plugin_src.js";s:4:"8a70";s:36:"res/tiny_mce/plugins/media/media.htm";s:4:"be58";s:42:"res/tiny_mce/plugins/media/moxieplayer.swf";s:4:"9217";s:42:"res/tiny_mce/plugins/media/css/content.css";s:4:"ebca";s:40:"res/tiny_mce/plugins/media/css/media.css";s:4:"f211";s:40:"res/tiny_mce/plugins/media/img/flash.gif";s:4:"6c69";s:45:"res/tiny_mce/plugins/media/img/flv_player.swf";s:4:"fe01";s:44:"res/tiny_mce/plugins/media/img/quicktime.gif";s:4:"9a6a";s:44:"res/tiny_mce/plugins/media/img/realmedia.gif";s:4:"b973";s:44:"res/tiny_mce/plugins/media/img/shockwave.gif";s:4:"baa6";s:40:"res/tiny_mce/plugins/media/img/trans.gif";s:4:"12bf";s:47:"res/tiny_mce/plugins/media/img/windowsmedia.gif";s:4:"c327";s:38:"res/tiny_mce/plugins/media/js/embed.js";s:4:"39eb";s:38:"res/tiny_mce/plugins/media/js/media.js";s:4:"e1b3";s:42:"res/tiny_mce/plugins/media/langs/de_dlg.js";s:4:"0ec1";s:42:"res/tiny_mce/plugins/media/langs/en_dlg.js";s:4:"9523";s:49:"res/tiny_mce/plugins/nonbreaking/editor_plugin.js";s:4:"232f";s:53:"res/tiny_mce/plugins/nonbreaking/editor_plugin_src.js";s:4:"fefb";s:49:"res/tiny_mce/plugins/noneditable/editor_plugin.js";s:4:"8b2a";s:53:"res/tiny_mce/plugins/noneditable/editor_plugin_src.js";s:4:"63cc";s:47:"res/tiny_mce/plugins/pagebreak/editor_plugin.js";s:4:"8be3";s:51:"res/tiny_mce/plugins/pagebreak/editor_plugin_src.js";s:4:"c2d4";s:46:"res/tiny_mce/plugins/pagebreak/css/content.css";s:4:"d966";s:48:"res/tiny_mce/plugins/pagebreak/img/pagebreak.gif";s:4:"4887";s:44:"res/tiny_mce/plugins/pagebreak/img/trans.gif";s:4:"12bf";s:43:"res/tiny_mce/plugins/paste/editor_plugin.js";s:4:"ace4";s:47:"res/tiny_mce/plugins/paste/editor_plugin_src.js";s:4:"b8ac";s:40:"res/tiny_mce/plugins/paste/pastetext.htm";s:4:"8f21";s:40:"res/tiny_mce/plugins/paste/pasteword.htm";s:4:"8b94";s:42:"res/tiny_mce/plugins/paste/js/pastetext.js";s:4:"d6e4";s:42:"res/tiny_mce/plugins/paste/js/pasteword.js";s:4:"1125";s:42:"res/tiny_mce/plugins/paste/langs/de_dlg.js";s:4:"d29b";s:42:"res/tiny_mce/plugins/paste/langs/en_dlg.js";s:4:"6ea2";s:45:"res/tiny_mce/plugins/preview/editor_plugin.js";s:4:"9252";s:49:"res/tiny_mce/plugins/preview/editor_plugin_src.js";s:4:"6f9c";s:41:"res/tiny_mce/plugins/preview/example.html";s:4:"9b92";s:41:"res/tiny_mce/plugins/preview/preview.html";s:4:"bb02";s:46:"res/tiny_mce/plugins/preview/jscripts/embed.js";s:4:"39eb";s:43:"res/tiny_mce/plugins/print/editor_plugin.js";s:4:"53eb";s:47:"res/tiny_mce/plugins/print/editor_plugin_src.js";s:4:"f115";s:42:"res/tiny_mce/plugins/save/editor_plugin.js";s:4:"307a";s:46:"res/tiny_mce/plugins/save/editor_plugin_src.js";s:4:"4dcb";s:51:"res/tiny_mce/plugins/searchreplace/editor_plugin.js";s:4:"ed4f";s:55:"res/tiny_mce/plugins/searchreplace/editor_plugin_src.js";s:4:"7292";s:52:"res/tiny_mce/plugins/searchreplace/searchreplace.htm";s:4:"3b9c";s:56:"res/tiny_mce/plugins/searchreplace/css/searchreplace.css";s:4:"ad0a";s:54:"res/tiny_mce/plugins/searchreplace/js/searchreplace.js";s:4:"9adf";s:50:"res/tiny_mce/plugins/searchreplace/langs/de_dlg.js";s:4:"7052";s:50:"res/tiny_mce/plugins/searchreplace/langs/en_dlg.js";s:4:"fbd4";s:50:"res/tiny_mce/plugins/spellchecker/editor_plugin.js";s:4:"50a8";s:54:"res/tiny_mce/plugins/spellchecker/editor_plugin_src.js";s:4:"432a";s:49:"res/tiny_mce/plugins/spellchecker/css/content.css";s:4:"ac0c";s:47:"res/tiny_mce/plugins/spellchecker/img/wline.gif";s:4:"c136";s:43:"res/tiny_mce/plugins/style/editor_plugin.js";s:4:"ac96";s:47:"res/tiny_mce/plugins/style/editor_plugin_src.js";s:4:"f3fe";s:36:"res/tiny_mce/plugins/style/props.htm";s:4:"14ad";s:40:"res/tiny_mce/plugins/style/css/props.css";s:4:"3442";s:38:"res/tiny_mce/plugins/style/js/props.js";s:4:"e124";s:42:"res/tiny_mce/plugins/style/langs/de_dlg.js";s:4:"10ff";s:42:"res/tiny_mce/plugins/style/langs/en_dlg.js";s:4:"f7d2";s:46:"res/tiny_mce/plugins/tabfocus/editor_plugin.js";s:4:"dccf";s:50:"res/tiny_mce/plugins/tabfocus/editor_plugin_src.js";s:4:"8cf5";s:35:"res/tiny_mce/plugins/table/cell.htm";s:4:"f427";s:43:"res/tiny_mce/plugins/table/editor_plugin.js";s:4:"6230";s:47:"res/tiny_mce/plugins/table/editor_plugin_src.js";s:4:"bb38";s:42:"res/tiny_mce/plugins/table/merge_cells.htm";s:4:"f939";s:34:"res/tiny_mce/plugins/table/row.htm";s:4:"fdfc";s:36:"res/tiny_mce/plugins/table/table.htm";s:4:"88fb";s:39:"res/tiny_mce/plugins/table/css/cell.css";s:4:"5639";s:38:"res/tiny_mce/plugins/table/css/row.css";s:4:"81a7";s:40:"res/tiny_mce/plugins/table/css/table.css";s:4:"f5e6";s:37:"res/tiny_mce/plugins/table/js/cell.js";s:4:"7103";s:44:"res/tiny_mce/plugins/table/js/merge_cells.js";s:4:"3650";s:36:"res/tiny_mce/plugins/table/js/row.js";s:4:"9c06";s:38:"res/tiny_mce/plugins/table/js/table.js";s:4:"4a2d";s:42:"res/tiny_mce/plugins/table/langs/de_dlg.js";s:4:"a852";s:42:"res/tiny_mce/plugins/table/langs/en_dlg.js";s:4:"ee34";s:39:"res/tiny_mce/plugins/template/blank.htm";s:4:"9553";s:46:"res/tiny_mce/plugins/template/editor_plugin.js";s:4:"70cb";s:50:"res/tiny_mce/plugins/template/editor_plugin_src.js";s:4:"336a";s:42:"res/tiny_mce/plugins/template/template.htm";s:4:"b55e";s:46:"res/tiny_mce/plugins/template/css/template.css";s:4:"5b2c";s:44:"res/tiny_mce/plugins/template/js/template.js";s:4:"75ab";s:45:"res/tiny_mce/plugins/template/langs/de_dlg.js";s:4:"49ce";s:45:"res/tiny_mce/plugins/template/langs/en_dlg.js";s:4:"1ce0";s:54:"res/tiny_mce/plugins/typo3filemanager/editor_plugin.js";s:4:"88c3";s:58:"res/tiny_mce/plugins/typo3filemanager/editor_plugin_src.js";s:4:"c4e7";s:56:"res/tiny_mce/plugins/typo3filemanager/img/typo3image.gif";s:4:"bf3e";s:55:"res/tiny_mce/plugins/typo3filemanager/img/typo3link.gif";s:4:"13c2";s:49:"res/tiny_mce/plugins/typo3filemanager/langs/de.js";s:4:"65ca";s:49:"res/tiny_mce/plugins/typo3filemanager/langs/en.js";s:4:"d246";s:49:"res/tiny_mce/plugins/visualchars/editor_plugin.js";s:4:"e494";s:53:"res/tiny_mce/plugins/visualchars/editor_plugin_src.js";s:4:"f285";s:47:"res/tiny_mce/plugins/wordcount/editor_plugin.js";s:4:"75fd";s:51:"res/tiny_mce/plugins/wordcount/editor_plugin_src.js";s:4:"289b";s:40:"res/tiny_mce/plugins/xhtmlxtras/abbr.htm";s:4:"e514";s:43:"res/tiny_mce/plugins/xhtmlxtras/acronym.htm";s:4:"ec63";s:46:"res/tiny_mce/plugins/xhtmlxtras/attributes.htm";s:4:"cf54";s:40:"res/tiny_mce/plugins/xhtmlxtras/cite.htm";s:4:"1468";s:39:"res/tiny_mce/plugins/xhtmlxtras/del.htm";s:4:"81bb";s:48:"res/tiny_mce/plugins/xhtmlxtras/editor_plugin.js";s:4:"c9f9";s:52:"res/tiny_mce/plugins/xhtmlxtras/editor_plugin_src.js";s:4:"b8b8";s:39:"res/tiny_mce/plugins/xhtmlxtras/ins.htm";s:4:"47e7";s:50:"res/tiny_mce/plugins/xhtmlxtras/css/attributes.css";s:4:"abc1";s:45:"res/tiny_mce/plugins/xhtmlxtras/css/popup.css";s:4:"ed53";s:42:"res/tiny_mce/plugins/xhtmlxtras/js/abbr.js";s:4:"d91a";s:45:"res/tiny_mce/plugins/xhtmlxtras/js/acronym.js";s:4:"0a19";s:48:"res/tiny_mce/plugins/xhtmlxtras/js/attributes.js";s:4:"e75f";s:42:"res/tiny_mce/plugins/xhtmlxtras/js/cite.js";s:4:"ba39";s:41:"res/tiny_mce/plugins/xhtmlxtras/js/del.js";s:4:"be96";s:52:"res/tiny_mce/plugins/xhtmlxtras/js/element_common.js";s:4:"b817";s:41:"res/tiny_mce/plugins/xhtmlxtras/js/ins.js";s:4:"6148";s:47:"res/tiny_mce/plugins/xhtmlxtras/langs/de_dlg.js";s:4:"9936";s:47:"res/tiny_mce/plugins/xhtmlxtras/langs/en_dlg.js";s:4:"45db";s:38:"res/tiny_mce/themes/advanced/about.htm";s:4:"ff13";s:39:"res/tiny_mce/themes/advanced/anchor.htm";s:4:"79d4";s:40:"res/tiny_mce/themes/advanced/charmap.htm";s:4:"be71";s:45:"res/tiny_mce/themes/advanced/color_picker.htm";s:4:"99f4";s:47:"res/tiny_mce/themes/advanced/editor_template.js";s:4:"dff6";s:51:"res/tiny_mce/themes/advanced/editor_template_src.js";s:4:"c26c";s:38:"res/tiny_mce/themes/advanced/image.htm";s:4:"2cb9";s:37:"res/tiny_mce/themes/advanced/link.htm";s:4:"65c5";s:42:"res/tiny_mce/themes/advanced/shortcuts.htm";s:4:"2bae";s:46:"res/tiny_mce/themes/advanced/source_editor.htm";s:4:"c417";s:48:"res/tiny_mce/themes/advanced/img/colorpicker.jpg";s:4:"9bcc";s:42:"res/tiny_mce/themes/advanced/img/flash.gif";s:4:"33ad";s:42:"res/tiny_mce/themes/advanced/img/icons.gif";s:4:"7316";s:43:"res/tiny_mce/themes/advanced/img/iframe.gif";s:4:"a1af";s:46:"res/tiny_mce/themes/advanced/img/pagebreak.gif";s:4:"4887";s:46:"res/tiny_mce/themes/advanced/img/quicktime.gif";s:4:"61da";s:46:"res/tiny_mce/themes/advanced/img/realmedia.gif";s:4:"b973";s:46:"res/tiny_mce/themes/advanced/img/shockwave.gif";s:4:"1ce7";s:42:"res/tiny_mce/themes/advanced/img/trans.gif";s:4:"12bf";s:42:"res/tiny_mce/themes/advanced/img/video.gif";s:4:"f85c";s:49:"res/tiny_mce/themes/advanced/img/windowsmedia.gif";s:4:"c327";s:40:"res/tiny_mce/themes/advanced/js/about.js";s:4:"606c";s:41:"res/tiny_mce/themes/advanced/js/anchor.js";s:4:"627d";s:42:"res/tiny_mce/themes/advanced/js/charmap.js";s:4:"02f4";s:47:"res/tiny_mce/themes/advanced/js/color_picker.js";s:4:"ff64";s:40:"res/tiny_mce/themes/advanced/js/image.js";s:4:"dee2";s:39:"res/tiny_mce/themes/advanced/js/link.js";s:4:"ba62";s:48:"res/tiny_mce/themes/advanced/js/source_editor.js";s:4:"f186";s:40:"res/tiny_mce/themes/advanced/langs/de.js";s:4:"8301";s:44:"res/tiny_mce/themes/advanced/langs/de_dlg.js";s:4:"12c0";s:40:"res/tiny_mce/themes/advanced/langs/en.js";s:4:"58c8";s:44:"res/tiny_mce/themes/advanced/langs/en_dlg.js";s:4:"0826";s:54:"res/tiny_mce/themes/advanced/skins/default/content.css";s:4:"1779";s:53:"res/tiny_mce/themes/advanced/skins/default/dialog.css";s:4:"416e";s:49:"res/tiny_mce/themes/advanced/skins/default/ui.css";s:4:"e917";s:58:"res/tiny_mce/themes/advanced/skins/default/img/buttons.png";s:4:"33b2";s:56:"res/tiny_mce/themes/advanced/skins/default/img/items.gif";s:4:"d201";s:61:"res/tiny_mce/themes/advanced/skins/default/img/menu_arrow.gif";s:4:"e217";s:61:"res/tiny_mce/themes/advanced/skins/default/img/menu_check.gif";s:4:"c7d0";s:59:"res/tiny_mce/themes/advanced/skins/default/img/progress.gif";s:4:"50c5";s:55:"res/tiny_mce/themes/advanced/skins/default/img/tabs.gif";s:4:"6473";s:59:"res/tiny_mce/themes/advanced/skins/highcontrast/content.css";s:4:"8294";s:58:"res/tiny_mce/themes/advanced/skins/highcontrast/dialog.css";s:4:"993c";s:54:"res/tiny_mce/themes/advanced/skins/highcontrast/ui.css";s:4:"2757";s:51:"res/tiny_mce/themes/advanced/skins/o2k7/content.css";s:4:"c696";s:50:"res/tiny_mce/themes/advanced/skins/o2k7/dialog.css";s:4:"d960";s:46:"res/tiny_mce/themes/advanced/skins/o2k7/ui.css";s:4:"40bd";s:52:"res/tiny_mce/themes/advanced/skins/o2k7/ui_black.css";s:4:"ba8d";s:53:"res/tiny_mce/themes/advanced/skins/o2k7/ui_silver.css";s:4:"b42c";s:57:"res/tiny_mce/themes/advanced/skins/o2k7/img/button_bg.png";s:4:"36fd";s:63:"res/tiny_mce/themes/advanced/skins/o2k7/img/button_bg_black.png";s:4:"9645";s:64:"res/tiny_mce/themes/advanced/skins/o2k7/img/button_bg_silver.png";s:4:"15fb";s:45:"res/tiny_mce/themes/simple/editor_template.js";s:4:"3ac3";s:49:"res/tiny_mce/themes/simple/editor_template_src.js";s:4:"409e";s:40:"res/tiny_mce/themes/simple/img/icons.gif";s:4:"273d";s:38:"res/tiny_mce/themes/simple/langs/de.js";s:4:"fa14";s:38:"res/tiny_mce/themes/simple/langs/en.js";s:4:"50dc";s:52:"res/tiny_mce/themes/simple/skins/default/content.css";s:4:"0f70";s:47:"res/tiny_mce/themes/simple/skins/default/ui.css";s:4:"c46c";s:49:"res/tiny_mce/themes/simple/skins/o2k7/content.css";s:4:"eb7a";s:44:"res/tiny_mce/themes/simple/skins/o2k7/ui.css";s:4:"f0ec";s:55:"res/tiny_mce/themes/simple/skins/o2k7/img/button_bg.png";s:4:"405c";s:38:"res/tiny_mce/utils/editable_selects.js";s:4:"e760";s:32:"res/tiny_mce/utils/form_utils.js";s:4:"579b";s:28:"res/tiny_mce/utils/mctabs.js";s:4:"51b9";s:30:"res/tiny_mce/utils/validate.js";s:4:"bfba";s:34:"res/tinymce_templates/advanced.php";s:4:"c6fd";s:35:"res/tinymce_templates/getParams.php";s:4:"fd49";s:44:"res/tinymce_templates/locallang_advanced.xml";s:4:"cd06";s:40:"res/tinymce_templates/simpleReplace.html";s:4:"214f";s:33:"res/tinymce_templates/static.html";s:4:"38e4";s:14:"static/full.ts";s:4:"3132";s:17:"static/minimal.ts";s:4:"1036";s:18:"static/pageLoad.ts";s:4:"ebf5";s:22:"static/pageTSConfig.ts";s:4:"7f3e";s:23:"static/setupTSConfig.ts";s:4:"333d";s:15:"static/small.ts";s:4:"a05c";s:18:"static/standard.ts";s:4:"3e1d";}',
);

?>