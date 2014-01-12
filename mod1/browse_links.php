<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 1999-2005 Kasper Skaarhoj (kasperYYYY@typo3.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
/**
 * Displays the page/file tree for browsing database records or files.
 * Used from TCEFORMS an other elements
 * In other words: This is the ELEMENT BROWSER!
 *
 * $Id: browse_links.php,v 1.20 2005/04/01 14:37:08 typo3 Exp $
 * Revised for TYPO3 3.6 November/2003 by Kasper Skaarhoj
 * XHTML compliant
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
require ('conf.php');
require ($BACK_PATH . 'init.php');
require_once ($BACK_PATH . 'template.php');
require_once (PATH_t3lib . 'class.t3lib_browsetree.php');
require_once (PATH_t3lib . 'class.t3lib_foldertree.php');
require_once (PATH_t3lib . 'class.t3lib_stdgraphic.php');
require_once (PATH_t3lib . 'class.t3lib_basicfilefunc.php');
$LANG->includeLLFile('EXT:tinymce_rte/mod1/locallang_browse_links.xml');

// Include classes
require_once (PATH_t3lib . 'class.t3lib_page.php');
require_once (PATH_t3lib . 'class.t3lib_recordlist.php');
require_once ($BACK_PATH . 'class.db_list.inc');
require_once ($BACK_PATH . 'class.db_list_extra.inc');

require_once ('../class.tx_tinymce_rte_base.php');

class tinymce_rte_template extends template {
	
}

/**
 * Local version of the record list.
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class TBE_browser_recordList extends \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList {

	var $thisScript = 'browse_links.php';

	/**
	 * Initializes the script path
	 *
	 * @return	void
	 */
	function __construct() {
		$this->thisScript = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('SCRIPT_NAME');
	}

	/**
	 * Creates the URL for links
	 *
	 * @param	mixed		If not blank string, this is used instead of $this->id as the id value.
	 * @param	string		If this is "-1" then $this->table is used, otherwise the value of the input variable.
	 * @param	string		Commalist of fields NOT to pass as parameters (currently "sortField" and "sortRev")
	 * @return	string		Query-string for URL
	 */
	function listURL($altId = '', $table = -1, $exclList = '') {
		return $this->thisScript .
				'?id=' . (strcmp($altId, '') ? $altId : $this->id) .
				'&table=' . rawurlencode($table == -1 ? $this->table : $table) .
				($this->thumbs ? '&imagemode=' . $this->thumbs : '') .
				($this->searchString ? '&search_field=' . rawurlencode($this->searchString) : '') .
				($this->searchLevels ? '&search_levels=' . rawurlencode($this->searchLevels) : '') .
				((!$exclList || !\TYPO3\CMS\Core\Utility\GeneralUtility::inList($exclList, 'sortField')) && $this->sortField ? '&sortField=' . rawurlencode($this->sortField) : '') .
				((!$exclList || !\TYPO3\CMS\Core\Utility\GeneralUtility::inList($exclList, 'sortRev')) && $this->sortRev ? '&sortRev=' . rawurlencode($this->sortRev) : '') .
				// extra:
				$this->ext_addP()
		;
	}

	/**
	 * Returns additional, local GET parameters to include in the links of the record list.
	 *
	 * @return	string
	 */
	function ext_addP() {
		$str = '&act=' . $GLOBALS['SOBE']->act .
				'&mode=' . $GLOBALS['SOBE']->mode .
				'&expandPage=' . $GLOBALS['SOBE']->expandPage .
				'&bparams=' . rawurlencode($GLOBALS['SOBE']->bparams);
		return $str;
	}

	/**
	 * Returns the title (based on $code) of a record (from table $table) with the proper link around (that is for "pages"-records a link to the level of that record...)
	 *
	 * @param	string		Table name
	 * @param	integer		UID (not used here)
	 * @param	string		Title string
	 * @param	array		Records array (from table name)
	 * @return	string
	 */
	function linkWrapItems($table, $uid, $code, $row) {
		global $TCA, $BACK_PATH;

		if (!$code) {
			$code = '<i>[' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.no_title', 1) . ']</i>';
		} else {
			$code = htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($code, $this->fixedL));
		}

		$title = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle($table, $row, FALSE, TRUE);

		$ficon = $BACK_PATH . \TYPO3\CMS\Backend\Utility\IconUtility::getIcon($table, $row);
		$aOnClick = "return insertElement('" . $table . "', '" . $row['uid'] . "', 'db', unescape('" . rawurlencode($title) . "'), '', '', '" . $ficon . "');";
		$ATag = '<a href="#" onclick="' . htmlspecialchars($aOnClick) . '">';
		$ATag_alt = substr($ATag, 0, -4) . ',\'\',1);">';
		$ATag_e = '</a>';

		return $ATag .
				'<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/plusbullet2.gif', 'width="18" height="16"') . ' title="' . $GLOBALS['LANG']->getLL('addToList', 1) . '" alt="" />' .
				$ATag_e .
				$ATag_alt .
				$code .
				$ATag_e;
	}

	/**
	 * Returns the title (based on $code) of a table ($table) without a link
	 *
	 * @param	string		Table name
	 * @param	string		Table label
	 * @return	string		The linked table label
	 */
	function linkWrapTable($table, $code) {
		return $code;
	}

	/**
	 * Local version that sets allFields to true to support userFieldSelect
	 *
	 * @return	void
	 * @see fieldSelectBox
	 */
	function generateList() {
		$this->allFields = true;
		parent::generateList();
	}

}

/**
 * Class which generates the page tree
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class localPageTree extends \TYPO3\CMS\Backend\Tree\View\BrowseTreeView {

	/**
	 * Constructor. Just calling init()
	 *
	 * @return	void
	 */
	function __construct() {
		$this->thisScript = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('SCRIPT_NAME');
		$this->init();

		$this->clause = ' AND doktype!=255' . $this->clause;
	}

	/**
	 * Wrapping the title in a link, if applicable.
	 *
	 * @param	string		Title, (must be ready for output, that means it must be htmlspecialchars()'ed).
	 * @param	array		The record
	 * @param	boolean		(Ignore)
	 * @return	string		Wrapping title string.
	 */
	function wrapTitle($title, $v, $ext_pArrPages = '') {
		if ($this->ext_isLinkable($v['doktype'], $v['uid'])) {
			$aOnClick = "return link_insert('" . $v['uid'] . "');";
			return '<a href="#" onclick="' . htmlspecialchars($aOnClick) . '">' . $title . '</a>';
		} else {
			return '<span style="color: #666666;">' . $title . '</span>';
		}
	}

	/**
	 * Create the page navigation tree in HTML
	 *
	 * @param	array		Tree array
	 * @return	string		HTML output.
	 */
	function printTree($treeArr = '') {
		global $BACK_PATH, $P;
		$titleLen = 30;  // crop title to this length

		if ($GLOBALS['BE_USER']->uc['titleLen'])
			$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
		if (!is_array($treeArr))
			$treeArr = $this->tree;

		$out = '';
		$c = 0;
		foreach ($treeArr as $k => $v) {
			$c++;

			if (str_replace("#" . $GLOBALS['SOBE']->curUrlInfo['cElement'], "", $GLOBALS['SOBE']->curUrlInfo['info']) == $v['row']['uid'] && $GLOBALS['SOBE']->curUrlInfo['value']) {
				$current = 'style="background: #b7bac0;"';
				//$arrCol='<td><img'.\TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH,'gfx/blinkarrow_right.gif','width="5" height="9"').' class="c-blinkArrowR" alt="" /></td>';
			} else {
				$current = '';
				//$arrCol='<td></td>';
			}



			$aOnClick = 'return jumpToUrl(\'' . $this->thisScript . '?act=' . $GLOBALS['SOBE']->act . '&mode=' . $GLOBALS['SOBE']->mode . '&expandPage=' . $v['row']['uid'] . '\');';
			$cEbullet = $this->ext_isLinkable($v['row']['doktype'], $v['row']['uid']) ?
					'<a href="#" onclick="' . htmlspecialchars($aOnClick) . '"><img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/ol/arrowbullet.gif', 'width="18" height="16"') . ' alt="" /></a>' :
					'';
			$out.='
				<tr ' . $current . '>
					<td nowrap="nowrap" style="width: 97%;" title="' . $v['row']["title"] . '">' .
					$v['HTML'] .
					$this->wrapTitle($this->getTitleStr($v['row'], $titleLen), $v['row'], $this->ext_pArrPages) .
					'</td>' .
					'<td>' . $cEbullet . '</td>
				</tr>';
		}
		$out = '


			<!--
				Navigation Page Tree:
			-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree" style="width: 100%; margin: 0;">
				' . $out . '
			</table>';
		return $out;
	}

	/**
	 * Returns true if a doktype can be linked.
	 *
	 * @param	integer		Doktype value to test
	 * @param	integer		uid to test.
	 * @return	boolean
	 */
	function ext_isLinkable($doktype, $uid) {
		if (($uid && $doktype < 199) || ($doktype == 254)) {
			return true;
		}
	}

	/**
	 * Wrap the plus/minus icon in a link
	 *
	 * @param	string		HTML string to wrap, probably an image tag.
	 * @param	string		Command for 'PM' get var
	 * @param	boolean		If set, the link will have a anchor point (=$bMark) and a name attribute (=$bMark)
	 * @return	string		Link-wrapped input string
	 */
	function PM_ATagWrap($icon, $cmd, $bMark = '') {
		if ($bMark) {
			$anchor = '#' . $bMark;
			$name = ' name="' . $bMark . '"';
		}
		$aOnClick = "return jumpToUrl('" . $this->thisScript . '?PM=' . $cmd . "','" . $anchor . "');";

		return '<a href="#"' . $name . ' onclick="' . htmlspecialchars($aOnClick) . '">' . $icon . '</a>';
	}

	/**
	 * Wrapping the image tag, $icon, for the row, $row
	 *
	 * @param	string		The image tag for the icon
	 * @param	array		The row for the current element
	 * @return	string		The processed icon input value.
	 */
	function wrapIcon($icon, $row) {
		return $this->addTagAttributes($icon, ' title="id=' . $row['uid'] . '"');
	}

}

/**
 * Page tree for the RTE - totally the same, no changes needed. (Just for the sake of beauty - or confusion... :-)
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class rtePageTree extends localPageTree {
	
}

/**
 * For TBE record browser
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class TBE_PageTree extends localPageTree {

	/**
	 * Returns true if a doktype can be linked (which is always the case here).
	 *
	 * @param	integer		Doktype value to test
	 * @param	integer		uid to test.
	 * @return	boolean
	 */
	function ext_isLinkable($doktype, $uid) {
		return true;
	}

	/**
	 * Wrapping the title in a link, if applicable.
	 *
	 * @param	string		Title, ready for output.
	 * @param	array		The record
	 * @param	boolean		If set, pages clicked will return immediately, otherwise reload page.
	 * @return	string		Wrapping title string.
	 */
	function wrapTitle($title, $v, $ext_pArrPages) {
		global $BACK_PATH;
		if ($ext_pArrPages) {
			$ficon = $BACK_PATH . \TYPO3\CMS\Backend\Utility\IconUtility::getIcon('pages', $v);
			$onClick = "return insertElement('pages', '" . $v['uid'] . "', 'db', unescape('" . rawurlencode($v['title']) . "'), '', '', '" . $ficon . "','',1);";
		} else {
			$onClick = 'return jumpToUrl(\'' . $this->thisScript . '?act=' . $GLOBALS['SOBE']->act . '&mode=' . $GLOBALS['SOBE']->mode . '&expandPage=' . $v['uid'] . '\');';
		}
		return '<a href="#" onclick="' . htmlspecialchars($onClick) . '">' . $title . '</a>';
	}

}

/**
 * Base extension class which generates the folder tree.
 * Used directly by the RTE.
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class localFolderTree extends TYPO3\CMS\Backend\Tree\View\FolderTreeView {

	var $ext_IconMode = 1;

	/**
	 * Initializes the script path
	 *
	 * @return	void
	 */
	function __construct() {
		$this->thisScript = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('SCRIPT_NAME');
		parent::__construct();
	}

	/**
	 * Wrapping $title in a-tags.
	 *
	 * @param string $title Title string
	 * @param \TYPO3\CMS\Core\Resource\Folder	$folderObject the folder record
	 * @param integer $bank Bank pointer (which mount point number)
	 * @return string
	 * @internal
	 */
	public function wrapTitle($title, \TYPO3\CMS\Core\Resource\Folder $folderObject, $bank = 0) {
		if ($this->ext_isLinkable($folderObject)) {
			$aOnClick = 'return jumpToUrl(\'' . $this->thisScript . '?act=' . $GLOBALS['SOBE']->act . '&mode=' . $GLOBALS['SOBE']->mode . '&expandFolder=' . rawurlencode($folderObject->getCombinedIdentifier()) . '\');';
			return '<a href="#" onclick="' . htmlspecialchars($aOnClick) . '">' . $title . '</a>';
		} else {
			return '<span class="typo3-dimmed">' . $title . '</span>';
		}
	}

	/**
	 * Returns TRUE if the input "record" contains a folder which can be linked.
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder $folderObject Object with information about the folder element. Contains keys like title, uid, path, _title
	 * @return boolean TRUE is returned if the path is found in the web-part of the server and is NOT a recycler or temp folder
	 * @todo Define visibility
	 */
	public function ext_isLinkable(\TYPO3\CMS\Core\Resource\Folder $folderObject) {
		if (!$folderObject->getStorage()->isPublic() || strstr($folderObject->getIdentifier(), '_recycler_') || strstr($folderObject->getIdentifier(), '_temp_')) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Wrap the plus/minus icon in a link
	 *
	 * @param string $icon HTML string to wrap, probably an image tag.
	 * @param string $cmd Command for 'PM' get var
	 * @param string $bMark If set, the link will have a anchor point (=$bMark) and a name attribute (=$bMark)
	 * @return string Link-wrapped input string
	 * @internal
	 */
	public function PMiconATagWrap($icon, $cmd, $bMark = '') {
		if ($this->thisScript) {
			// Activates dynamic AJAX based tree
			if ($bMark) {
				$anchor = '#' . $bMark;
				$name = ' name="' . $bMark . '"';
			}
			$aOnClick = 'return jumpToUrl(\'' . $this->thisScript . '?PM=' . $cmd . '\',\'' . $anchor . '\');';
			return '<a href="#"' . $name . ' onclick="' . htmlspecialchars($aOnClick) . '">' . $icon . '</a>';
		} else {
			return $icon;
		}
	}

}

/**
 * Folder tree for the RTE - totally the same, no changes needed. (Just for the sake of beauty - or confusion... :-)
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class rteFolderTree extends localFolderTree {
	
}

/**
 * For TBE File Browser
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class TBE_FolderTree extends localFolderTree {

	var $ext_noTempRecyclerDirs = 0;  // If file-drag mode is set, temp and recycler folders are filtered out.

	/**
	 * Returns true if the input "record" contains a folder which can be linked.
	 *
	 * @param	array		Array with information about the folder element. Contains keys like title, uid, path, _title
	 * @return	boolean		True is returned if the path is NOT a recycler or temp folder AND if ->ext_noTempRecyclerDirs is not set.
	 */
	// function ext_isLinkable($v)	{
	// if ($this->ext_noTempRecyclerDirs && (substr($v['path'],-7)=='_temp_/' || substr($v['path'],-11)=='_recycler_/'))	{
	// return 0;
	// } return 1;
	// }

	/**
	 * Wrapping the title in a link, if applicable.
	 *
	 * @param	string		Title, ready for output.
	 * @param	array		The 'record'
	 * @return	string		Wrapping title string.
	 */
	// function wrapTitle($title,$v)	{
	// if ($this->ext_isLinkable($v))	{
	// $aOnClick = 'return jumpToUrl(\''.$this->thisScript.'?act='.$GLOBALS['SOBE']->act.'&mode='.$GLOBALS['SOBE']->mode.'&expandFolder='.rawurlencode($v['path']).'\');';
	// return '<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$title.'</a>';
	// } else {
	// return '<span class="typo3-dimmed">'.$title.'</span>';
	// }
	// }
}

/**
 * Script class for the Element Browser window.
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class SC_browse_links extends \TYPO3\CMS\Recordlist\Browser\ElementBrowser {

	// Internal, static:
	var $siteURL;   // Current site URL (Frontend)
	var $thisScript;  // the script to link to
	var $thisConfig;  // RTE specific TSconfig
	var $setTarget;   // Target (RTE specific)
	var $setClass;   // CSS Class (RTE specific)
	var $setTitle;		// title (RTE specific)
	var $doc;   // Backend template object
	var $elements = array(); // Holds information about files
	var $fileProcessor = array(); // Makes possible to handle file processing extenstion
	// GPvars:	(Input variables from outside)
	/**
	 * The mode determines the main kind of output from the element browser.
	 * There are these options for values: rte, db, file, filedrag, wizard.
	 * "rte" will show the link selector for the Rich Text Editor (see main_rte())
	 * "db" will allow you to browse for pages or records in the page tree (for TCEforms, see main_db())
	 * "file"/"filedrag" will allow you to browse for files or folders in the folder mounts (for TCEforms, main_file())
	 * "wizard" will allow you to browse for links (like "rte") which are passed back to TCEforms (see main_rte(1))
	 *
	 * @see main()
	 */
	var $mode;

	/**
	 * Link selector action.
	 * page,file,url,mail,spec are allowed values.
	 * These are only important with the link selector function and in that case they switch between the various menu options.
	 */
	var $act;

	/**
	 * When you click a page title/expand icon to see the content of a certain page, this value will contain that value (the ID of the expanded page). If the value is NOT set, then it will be restored from the module session data (see main(), mode="db")
	 */
	var $expandPage;

	/**
	 * When you click a folder name/expand icon to see the content of a certain file folder, this value will contain that value (the path of the expanded file folder). If the value is NOT set, then it will be restored from the module session data (see main(), mode="file"/"filedrag"). Example value: "/www/htdocs/typo3/32/3dsplm/fileadmin/css/"
	 */
	var $expandFolder;

	/**
	 * TYPO3 Element Browser, wizard mode parameters. There is a heap of parameters there, better debug() them out if you need something... :-)
	 */
	var $P;

	/**
	 * Active with TYPO3 Element Browser: Contains the name of the form field for which this window opens - thus allows us to make references back to the main window in which the form is.
	 * Example value: "data[pages][39][bodytext]|||tt_content|" or "data[tt_content][NEW3fba56fde763d][image]|||gif,jpg,jpeg,tif,bmp,pcx,tga,png,pdf,ai|"
	 */
	var $bparams;

	/**
	 * Used with the Rich Text Editor.
	 * Example value: "tt_content:NEW3fba58c969f5c:bodytext:23:text:23:"
	 */
	var $RTEtsConfigParams;

	/**
	 * Plus/Minus icon value. Used by the tree class to open/close notes on the trees.
	 */
	var $PM;

	/**
	 * Pointer, used when browsing a long list of records etc.
	 */
	var $pointer;

	/**
	 * Used with the link selector: Contains the GET input information about the CURRENT link in the RTE/TCEform field. This consists of "href" and "target" keys. This information is passed around in links.
	 */
	var $curUrlArray;

	/**
	 * Used with the link selector: Contains a processed version of the input values from curUrlInfo. This is splitted into pageid, content element id, label value etc. This is used for the internal processing of that information.
	 */
	var $curUrlInfo;

	/**
	 * array which holds hook objects (initialised in init() )
	 */
	var $hookObjects = array();
	var $readOnly = FALSE; // If set, all operations that changes something should be disabled. This is used for alternativeBrowsing file mounts (see options like "options.folderTree.altElementBrowserMountPoints" in browse_links.php).

	function extended() {
		$this->init();
		$this->main();
		//$this->content= $this->content;
		$this->printContent();
		exit;
	}

	function init() {
		global $BE_USER, $BACK_PATH;
		$conf = NULL;

		// Main GPvars:
		$this->pointer = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('pointer');
		$this->bparams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('bparams');
		$this->P = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('P');
		$this->RTEtsConfigParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('RTEtsConfigParams');
		$this->expandPage = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('expandPage');
		$this->expandFolder = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('expandFolder');
		$this->PM = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('PM');

		// Find "mode"
		$this->mode = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('mode');
		if (!$this->mode) {
			$this->mode = 'rte';
		}

		// init hook objects:
		$this->hookObjects = array();
		if ((intval(phpversion()) >= 5) && (TYPO3_branch > 4.1))
			include_once('include_php5.inc');
		else
			include_once('include_php4.inc');

		// Site URL
		$this->siteURL = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'); // Current site url
		// Rich Text Editor specific configuration:
		$addPassOnParams = '';
		if ((string) $this->mode == 'wizard') {
			$RTEtsConfigParts = explode(':', $this->RTEtsConfigParams);
			$addPassOnParams.='&RTEtsConfigParams=' . rawurlencode($this->RTEtsConfigParams);
			$RTEsetup = $GLOBALS['BE_USER']->getTSConfig('RTE', \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($RTEtsConfigParts[5]));
			$this->thisConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::RTEsetup($RTEsetup['properties'], $RTEtsConfigParts[0], $RTEtsConfigParts[2], $RTEtsConfigParts[4]);
		}

		// the script to link to
		$this->thisScript = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('SCRIPT_NAME');

		// init fileProcessor
		$this->fileProcessor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('t3lib_basicFileFunctions');
		$this->fileProcessor->init($GLOBALS['FILEMOUNTS'], $GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);


		// CurrentUrl - the current link url must be passed around if it exists
		if ($this->mode == 'wizard') {
			$currentValues = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(LF, trim($this->P['currentValue']));
			if (count($currentValues) > 0) {
				$currentValue = array_pop($currentValues);
			} else {
				$currentValue = '';
			}
			$currentLinkParts = \TYPO3\CMS\Core\Utility\GeneralUtility::unQuoteFilenames($currentValue, TRUE);
			$initialCurUrlArray = array(
				'href' => $currentLinkParts[0],
				'target' => $currentLinkParts[1],
				'class' => $currentLinkParts[2],
				'title' => $currentLinkParts[3],
				'params' => $currentLinkParts[4],
			);

			$this->curUrlArray = is_array(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('curUrl')) ? array_merge($initialCurUrlArray, \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('curUrl')) : $initialCurUrlArray;
			// Additional fields for page links
			if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['extendUrlArray']) && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['extendUrlArray'])) {
				$_params = array(
					'conf' => &$conf,
					'linkParts' => $currentLinkParts
				);
				foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['extendUrlArray'] as $objRef) {
					$processor = & \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($objRef);
					$processor->extendUrlArray($_params, $this);
				}
			}
			$this->curUrlInfo = $this->parseCurUrl($this->siteURL . '?id=' . $this->curUrlArray['href'], $this->siteURL);

			if ($this->curUrlInfo['pageid'] == 0 && $this->curUrlArray['href']) { // pageid == 0 means that this is not an internal (page) link
				// Check if there is the FAL API
				if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($this->curUrlArray['href'], 'file:')) {
					$this->curUrlInfo = $this->parseCurUrl($this->curUrlArray['href'], $this->siteURL);
					// Remove the "file:" prefix
					$currentLinkParts[0] = rawurldecode(substr($this->curUrlArray['href'], 5));
				} elseif (file_exists(PATH_site . rawurldecode($this->curUrlArray['href']))) {
					if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($this->curUrlArray['href'], PATH_site)) {
						$currentLinkParts[0] = substr($this->curUrlArray['href'], strlen(PATH_site));
					}
					$this->curUrlInfo = $this->parseCurUrl($this->siteURL . $this->curUrlArray['href'], $this->siteURL);
				} elseif (strstr($this->curUrlArray['href'], '@')) {
					// check for email link
					if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($this->curUrlArray['href'], 'mailto:')) {
						$currentLinkParts[0] = substr($this->curUrlArray['href'], 7);
					}

					$this->curUrlInfo = $this->parseCurUrl('mailto:' . $this->curUrlArray['href'], $this->siteURL);
				} elseif (strstr($this->curUrlArray['href'], 'record:')) {
					$handel = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(':', $this->curUrlArray['href']);

					if (is_array($this->thisConfig['linkhandler.'][$handel[1] . '.'])) {

						$row = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($handel[1], $handel[2]);
						if (is_array($this->thisConfig['linkhandler.'][$handel[1] . '.'][$row['pid'] . '.'])) {
							$this->curUrlInfo['info'] = $row['pid'];
							$this->curUrlInfo['pageid'] = $this->curUrlInfo['info'];
							$this->expandPage();
						} else {
							$this->curUrlInfo = array();
						}
						$this->curUrlInfo['cElement'] = $handel[2];
					} else {
						echo "in PageTSconfig you should define RTE.default.linkhandler." . $handel[1];
						echo "<br /> Example Config tt_news:";
						echo "<pre>
RTE.default.linkhandler {
	tt_news {
		default {
			# instead of default you could write the id of the storage folder
			# id of the Single News Page
			parameter = 27
			additionalParams = &tx_ttnews[tt_news]={field:uid}
			additionalParams.insertData = 1
			# you need: uid, hidden, header [this is the displayed title] (use xx as header to select other properties)
			# you can provide: bodytext [alternative title], starttime, endtime [to display the current status]
			select = uid,title as header,hidden,starttime,endtime,bodytext
			sorting = crdate
		}
	}
}
</pre>";
						die(); //message to user; wrong config
					}
				} else { // nothing of the above. this is an external link
					if (strpos($this->curUrlArray['href'], '://') === false) {
						$currentLinkParts[0] = 'http://' . $this->curUrlArray['href'];
					}
					$this->curUrlInfo = $this->parseCurUrl($currentLinkParts[0], $this->siteURL);
				}
			} elseif (!$this->curUrlArray['href']) {
				$this->curUrlInfo = array();
				$this->act = 'page';
			} else {
				$this->curUrlInfo = $this->parseCurUrl($this->siteURL . '?id=' . $this->curUrlArray['href'], $this->siteURL);
				$this->curUrlInfo['info'] = $this->curUrlInfo['pageid'];
			}
		} else {
			$this->curUrlArray = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('curUrl');
			if ($this->curUrlArray['all']) {
				$this->curUrlArray = \TYPO3\CMS\Core\Utility\GeneralUtility::get_tag_attributes($this->curUrlArray['all']);
			}
			$this->curUrlInfo = $this->parseCurUrl($this->curUrlArray['href'], $this->siteURL);
		}

		// Determine nature of current url:
		$this->act = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('act');
		if (!$this->act) {
			$this->act = $this->curUrlInfo['act'];
		}




		// Initializing the target value (RTE)
		$this->setTarget = $this->curUrlArray['target'];
		if ($this->thisConfig['defaultLinkTarget'] && !isset($this->curUrlArray['target'])) {
			$this->setTarget = $this->thisConfig['defaultLinkTarget'];
		}

		// Initializing the class value (RTE)
		$this->setClass = ($this->curUrlArray['class'] != '-') ? $this->curUrlArray['class'] : '';

		// Initializing the title value (RTE)
		$this->setTitle = ($this->curUrlArray['title'] != '-') ? $this->curUrlArray['title'] : '';



		// Creating backend template object:
		$this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('template');
		$this->doc->docType = 'xhtml_trans';
		$this->doc->backPath = $BACK_PATH;

		// BEGIN accumulation of header JavaScript:
		$JScode = '';
		$JScode.= '
				// This JavaScript is primarily for RTE/Link. jumpToUrl is used in the other cases as well...
			var add_href="' . ($this->curUrlArray['href'] ? '&curUrl[href]=' . rawurlencode($this->curUrlArray['href']) : '') . '";
			var add_target="' . ($this->setTarget ? '&curUrl[target]=' . rawurlencode($this->setTarget) : '') . '";
			var add_params="' . ($this->bparams ? '&bparams=' . rawurlencode($this->bparams) : '') . '";

			var cur_href="' . ($this->curUrlArray['href'] ? $this->curUrlArray['href'] : '') . '";
			var cur_target="' . ($this->setTarget ? $this->setTarget : '') . '";
			var bm=tinyMCEPopup.editor.selection.getBookmark();
		';


		if ($this->mode == 'wizard') { // Functions used, if the link selector is in wizard mode (= TCEforms fields)
			unset($this->P['fieldChangeFunc']['alert']);
			reset($this->P['fieldChangeFunc']);
			$update = '';
			while (list($k, $v) = each($this->P['fieldChangeFunc'])) {
				$update.= '
						popWin.' . $v;
			}
			$update.='
						tinyMCEPopup.close();';

			$P2 = array();
			$P2['itemName'] = $this->P['itemName'];
			$P2['formName'] = $this->P['formName'];
			$P2['fieldChangeFunc'] = $this->P['fieldChangeFunc'];
			$P2['init'] = $this->P['init'];
			$P2['ext'] = $this->P['ext'];
			$P2['params']['allowedExtensions'] = $this->P['params']['allowedExtensions'];
			$P2['params']['blindLinkOptions'] = $this->P['params']['blindLinkOptions'];

			$addPassOnParams.=\TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('P', $P2);

			$JScode.='
			  function directSetHref(value) {
					// called directly from withint the content area
					tinyMCEPopup.execCommand("mceBeginUndoLevel");

					var inst = tinyMCE.activeEditor;
					var elm = inst.selection.getNode();
					elm = inst.dom.getParent(elm, "A");
					// Create new anchor elements
					if (elm == null) {
						tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#", {skip_undo : 1});
						var elementArray = tinymce.grep(inst.dom.select("a"), function(n) {return inst.dom.getAttrib(n, "href") == "#mce_temp_url#";});
						for (var i=0; i<elementArray.length; i++)
							tinyMCE.activeEditor.dom.setAttrib(elm = elementArray[i], "href", value);
					} else
						tinyMCE.activeEditor.dom.setAttrib(elm, "href", value);

					// Don t move caret if selection was image
					if (elm.childNodes.length != 1 || elm.firstChild.nodeName != "IMG") {
						inst.focus();
						inst.selection.select(elm);
						inst.selection.collapse(0);
						tinyMCEPopup.storeSelection();
					}

					tinyMCEPopup.execCommand("mceEndUndoLevel");
					return true;
				}

				function link_insert(value,anchor)	{
					if (!anchor) anchor = "";
					var win = tinyMCEPopup.getWindowArg("window");
					if (win) {
						win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = value + anchor;
					}	else {
						directSetHref( value + anchor );
					}
			';
			//miss use bparams
			if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('bparams') == 'media') {
				$JScode .= '
					// for media browsers: update media preview
					win.document.getElementById(tinyMCEPopup.getWindowArg("input")).onchange();
					// win.Media.formToData();

					if (tinyMCE.activeEditor.selection.getContent() === "") {
						tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.dom.select("p")[0]); // xxx no fucking clue why this is needed; but otherwise if nothing is select in the main text it will not insert the correct html code into the editor...
					}
				';
			}
			$JScode .= '
					tinyMCEPopup.close();
					return false;
				}

				function record_insert(type,value) {
					var win = tinyMCEPopup.getWindowArg("window");
					if (win)
						win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = "record:" + type + ":" + value;
					else
						directSetHref( "record:" + type + ":" + value );

					tinyMCEPopup.close();
					return false;
				}

			';
		} else { // Functions used, if the link selector is in RTE mode:
			$JScode.='
			';
		}

		// General "jumpToUrl" function:
		$JScode.='
			function jumpToUrl(URL,anchor)	{	//
				tinyMCEPopup.editor.selection.moveToBookmark(bm);
				var add_act = URL.indexOf("act=")==-1 ? "&act=' . $this->act . '" : "";
				var add_mode = URL.indexOf("mode=")==-1 ? "&mode=' . $this->mode . '" : "";
				//var theLocation = URL+add_act+add_mode+add_href+add_target+add_params' . ($addPassOnParams ? '+"' . $addPassOnParams . '"' : '') . '+(anchor?anchor:"");
				var theLocation = URL+add_act+add_mode+add_href+add_target+add_params' . ($addPassOnParams ? '+"' . $addPassOnParams . '"' : '') . ';
				document.location = theLocation;
				return false;
			}
		';

		// Finally, add the accumulated JavaScript to the template object:
		$this->doc->JScode .= '<script language="javascript" type="text/javascript" src="../res/tiny_mce/tiny_mce_popup.js"></script>';
		$this->doc->JScode .= $this->doc->wrapScriptTags($JScode);

		// Debugging:
		if (FALSE)
			debug(array(
				'pointer' => $this->pointer,
				'act' => $this->act,
				'mode' => $this->mode,
				'curUrlInfo' => $this->curUrlInfo,
				'curUrlArray' => $this->curUrlArray,
				'P' => $this->P,
				'bparams' => $this->bparams,
				'RTEtsConfigParams' => $this->RTEtsConfigParams,
				'expandPage' => $this->expandPage,
				'expandFolder' => $this->expandFolder,
				'PM' => $this->PM,
					), 'Internal variables of Script Class:');
	}

	/**
	 * Main function, detecting the current mode of the element browser and branching out to internal methods.
	 *
	 * @return	void
	 */
	function main() {
		global $BE_USER;
		$this->content = '';

		// look for alternativ mountpoints
		switch ((string) $this->mode) {
			case 'rte':
			case 'db':
			case 'wizard':
				// Setting alternative browsing mounts (ONLY local to browse_links.php this script so they stay "read-only")
				$altMountPoints = trim($GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.altElementBrowserMountPoints'));
				if ($altMountPoints) {
					$GLOBALS['BE_USER']->groupData['webmounts'] = implode(',', array_unique(\TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $altMountPoints)));
					$GLOBALS['WEBMOUNTS'] = $GLOBALS['BE_USER']->returnWebmounts();
				}
			case 'file':
			case 'filedrag':
			case 'folder':
				// Setting additional read-only browsing file mounts
				$altMountPoints = trim($GLOBALS['BE_USER']->getTSConfigVal('options.folderTree.altElementBrowserMountPoints'));
				if ($altMountPoints) {
					$altMountPoints = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $altMountPoints);
					foreach ($altMountPoints as $filePathRelativeToFileadmindir) {
						$GLOBALS['BE_USER']->addFileMount('', $filePathRelativeToFileadmindir, $filePathRelativeToFileadmindir, 1, 'readonly');
					}
					$GLOBALS['FILEMOUNTS'] = $GLOBALS['BE_USER']->returnFilemounts();
				}
				break;
		}


		// render type by user func
		$browserRendered = false;
		/* if (is_array ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/browse_links.php']['browserRendering'])) {
		  foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/browse_links.php']['browserRendering'] as $classRef) {
		  $browserRenderObj = \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($classRef);
		  if (is_object($browserRenderObj) && method_exists($browserRenderObj, 'isValid') && method_exists($browserRenderObj, 'render')) {
		  if ($browserRenderObj->isValid($this->mode, $this)) {
		  $this->content.= $browserRenderObj->render($this->mode, $this);
		  $browserRendered = true;
		  break;
		  }
		  }
		  }
		  } */

		// if type was not rendered use default rendering functions
		if (!$browserRendered) {
			$modData = $BE_USER->getModuleData('browse_links.php', 'ses');
			// Output the correct content according to $this->mode
			switch ((string) $this->mode) {
				case 'rte':
					$this->content = $this->main_rte();
					break;
				case 'db':
					if (isset($this->expandPage)) {
						$modData['expandPage'] = $this->expandPage;
						$BE_USER->pushModuleData('browse_links.php', $modData);
					} else {
						$this->expandPage = $modData['expandPage'];
					}

					$this->content = $this->main_db();
					break;
				case 'file':
				case 'filedrag':
				case 'folder':
					if (isset($this->expandFolder)) {
						$modData['expandFolder'] = $this->expandFolder;
						$BE_USER->pushModuleData('browse_links.php', $modData);
					} else {
						$this->expandFolder = $modData['expandFolder'];
					}

					$this->content = $this->main_file();
					break;
				case 'wizard':
					$this->content = $this->main_rte(1);
					break;
			}
		}
	}

	/**
	 * Print module content
	 *
	 * @return	void
	 */
	function printContent() {
		echo $this->content;
	}

	/*	 * ****************************************************************
	 *
	 * Menubar
	 *
	 * **************************************************************** */

	function getTabMenuRaw($menuItems) {
		$content = '';

		if (is_array($menuItems)) {
			$options = '';

			$count = count($menuItems);
			$widthLeft = 1;
			$addToAct = 5;

			$first = true;
			foreach ($menuItems as $id => $def) {
				$isActive = $def['isActive'];
				$xclass = $isActive ? 'current' : '';

				// @rene: Here you should probably wrap $label and $url in htmlspecialchars() in order to make sure its XHTML compatible! I did it for $url already since that is VERY likely to break.
				$label = $def['label'];
				$url = htmlspecialchars($def['url']);
				$params = $def['addParams'];

				if ($first) {
					$options.='<li class="' . $xclass . '"><span><a href="' . $url . '" style="padding-left:5px;padding-right:2px;" ' . $params . '>' . $label . '</a></span></li>';
				} else {
					$options.='<li class="' . $xclass . '"><span><a href="' . $url . '" ' . $params . '>' . $label . '</a></span></li>';
				}
				$first = false;
			}

			if ($options) {
				$content.='
				<!-- Tab menu -->
				<div class="tabs">
					<ul>
						' . $options . '
					</ul>
				</div>';
			}
		}
		return $content;
	}

	/*	 * ****************************************************************
	 *
	 * Main functions
	 *
	 * **************************************************************** */

	/**
	 * Rich Text Editor (RTE) link selector (MAIN function)
	 * Generates the link selector for the Rich Text Editor.
	 * Can also be used to select links for the TCEforms (see $wiz)
	 *
	 * @param	boolean		If set, the "remove link" is not shown in the menu: Used for the "Select link" wizard which is used by the TCEforms
	 * @return	string		Modified content variable.
	 */
	function main_rte($wiz = 1) {
		global $LANG, $BACK_PATH;

		#\TYPO3\CMS\Core\Utility\GeneralUtility::debug($_POST);
		#\TYPO3\CMS\Core\Utility\GeneralUtility::debug($_GET);
		// Starting content:
		$content = $this->doc->startPage($LANG->getLL('title', 1));

		// Initializing the action value, possibly removing blinded values etc:
		$allowedItems = array_diff(
				explode(',', 'page,file,folder,url,mail,spec'), \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->thisConfig['blindLinkOptions'], 1)
		);
		$allowedItems = array_diff(
				$allowedItems, \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->P['params']['blindLinkOptions'])
		);

		//call hook for extra options
		foreach ($this->hookObjects as $hookObject) {
			$allowedItems = $hookObject->addAllowedItems($allowedItems);
		}
		reset($allowedItems);
		if (!in_array($this->act, $allowedItems))
			$this->act = current($allowedItems);

		// Making menu in top:
		$menuDef = array();
		if (!$wiz) {
			$menuDef['removeLink']['isActive'] = $this->act == 'removeLink';
			$menuDef['removeLink']['label'] = $LANG->getLL('removeLink', 1);
			$menuDef['removeLink']['url'] = '#';
			$menuDef['removeLink']['addParams'] = 'onclick="self.parent.parent.renderPopup_unLink();return false;"';
		}
		if (in_array('page', $allowedItems)) {
			$menuDef['page']['isActive'] = $this->act == 'page';
			$menuDef['page']['label'] = $LANG->getLL('page', 1);
			$menuDef['page']['url'] = '#';
			$menuDef['page']['addParams'] = 'onclick="jumpToUrl(\'?act=page\');return false;"';
		}
		if (in_array('file', $allowedItems)) {
			$menuDef['file']['isActive'] = $this->act == 'file';
			$menuDef['file']['label'] = $LANG->getLL('file', 1);
			$menuDef['file']['url'] = '#';
			$menuDef['file']['addParams'] = 'onclick="jumpToUrl(\'?act=file\');return false;"';
		}
		if (in_array('url', $allowedItems)) {
			$menuDef['url']['isActive'] = $this->act == 'url';
			$menuDef['url']['label'] = $LANG->getLL('extUrl', 1);
			$menuDef['url']['url'] = '#';
			$menuDef['url']['addParams'] = 'onclick="jumpToUrl(\'?act=url\');return false;"';
		}
		if (in_array('mail', $allowedItems)) {
			$menuDef['mail']['isActive'] = $this->act == 'mail';
			$menuDef['mail']['label'] = $LANG->getLL('email', 1);
			$menuDef['mail']['url'] = '#';
			$menuDef['mail']['addParams'] = 'onclick="jumpToUrl(\'?act=mail\');return false;"';
		}
		// call hook for extra options
		foreach ($this->hookObjects as $hookObject) {
			$menuDef = $hookObject->modifyMenuDefinition($menuDef);
		}
		$content .= $this->getTabMenuRaw($menuDef);

		// add extra container
		$content .= '<div class="panel_wrapper">';

		// Adding the menu and header to the top of page:
		$content.=$this->printCurrentUrl($this->curUrlInfo['info']);


		// Depending on the current action we will create the actual module content for selecting a link:
		switch ($this->act) {
			case 'mail':
				$extUrl = '

			<!--
				Enter mail address:
			-->
					<fieldset>
					  <legend>' . $GLOBALS['LANG']->getLL('emailAddress', 1) . '</legend>
					    <form action="" name="lurlform" id="lurlform">
						<table border="0" cellpadding="2" cellspacing="1" id="typo3-linkMail">
							<tr>
								<td><input type="text" id="lemail" name="lemail"' . $this->doc->formWidth(20) . ' value="' . htmlspecialchars($this->curUrlInfo['value'] ? $this->curUrlInfo['info'] : '') . '" /> ' .
						'<input type="submit" value="' . $GLOBALS['LANG']->getLL('setLink', 1) . '" onclick="return link_insert(' . "'mailto:'+" . 'document.getElementById(' . "'lemail'" . ').value);" /></td>
							</tr>
						</table>
					    </form>
					</fieldset>';
				$content.=$extUrl;
				break;
			case 'url':
				$extUrl = '

			<!--
				Enter External URL:
			-->
					<fieldset>
					  <legend>URL</legend>
					    <form action="" name="lurlform" id="lurlform">
						<table border="0" cellpadding="2" cellspacing="1" id="typo3-linkURL">
							<tr>
								<td><input type="text" id="lurl" name="lurl"' . $this->doc->formWidth(20) . ' value="' . htmlspecialchars($this->curUrlInfo['value'] ? $this->curUrlInfo['info'] : 'http://') . '" /> ' .
						'<input type="submit" value="' . $GLOBALS['LANG']->getLL('setLink', 1) . '" onclick="return link_insert(document.getElementById(' . "'lurl'" . ').value);" /></td>
							</tr>
						</table>
					    </form>
					 </fieldset>';
				$content.=$extUrl;
				break;
			case 'file':
			case 'folder':
				/** @var rteFolderTree $foldertree */
				$foldertree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('rteFolderTree');
				$foldertree->thisScript = $this->thisScript;
				$tree = $foldertree->getBrowsableTree();

				if (!$this->curUrlInfo['value'] || $this->curUrlInfo['act'] != $this->act) {
					$cmpPath = '';
				} else {
					$cmpPath = $this->curUrlInfo['value'];
					if (!isset($this->expandFolder)) {
						$this->expandFolder = $cmpPath;
					}
				}


				// Get the selected folder
				$selectedFolder = FALSE;
				if ($this->expandFolder) {
					$fileOrFolderObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->retrieveFileOrFolderObject($this->expandFolder);
					if ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\Folder) {
						// it's a folder
						$selectedFolder = $fileOrFolderObject;
					} elseif ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\FileInterface) {
						// it's a file
						// @todo: find the parent folder, right now done a bit ugly, because the file does not
						// support finding the parent folder of a file on purpose
						$folderIdentifier = dirname($fileOrFolderObject->getIdentifier());
						$selectedFolder = $fileOrFolderObject->getStorage()->getFolder($folderIdentifier);
					}
				}
				// If no folder is selected, get the user's default upload folder
				if (!$selectedFolder) {
					$selectedFolder = $GLOBALS['BE_USER']->getDefaultUploadFolder();
				}
				// Render the filelist if there is a folder selected
				if ($selectedFolder) {
					$files = $this->expandFolder($selectedFolder, $this->P['params']['allowedExtensions']);
				}

				// list(,,$specUid) = explode('_',$this->PM);
				// $files = $this->expandFolder(
				// $foldertree->specUIDmap[$specUid],
				// $this->P['params']['allowedExtensions']
				// );
				$files = '<fieldset><legend>' . $GLOBALS['LANG']->getLL('files') . '</legend></legend><div style="overflow: auto;"><table style="width: 100%;"><tr><td>' . $files . '</td></tr></table></div></fieldset>';
				$content.= '

			<!--
				Wrapper table for folder tree / file list:
			-->
					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
						<tr>
							<td valign="top" style="width: 48%;"><fieldset><legend>' . $GLOBALS['LANG']->getLL('folderTree') . '</legend></legend><div style="overflow: auto;"><table style="width: 100%;"><tr><td>' . $tree . '</td></tr></table></div></fieldset></td>
							<td valign="top" style="width: 1%;"><img src="clear.gif" width="5" alt="" /></td>
							<td valign="top" style="width: 48%;">' . $files . '</td>
						</tr>
					</table>
					';
				break;
			case 'spec':
				if (is_array($this->thisConfig['userLinks.'])) {
					$subcats = array();
					$v = $this->thisConfig['userLinks.'];
					reset($v);
					while (list($k2) = each($v)) {
						$k2i = intval($k2);
						if (substr($k2, -1) == '.' && is_array($v[$k2i . '.'])) {

							// Title:
							$title = trim($v[$k2i]);
							if (!$title) {
								$title = $v[$k2i . '.']['url'];
							} else {
								$title = $LANG->sL($title);
							}
							// Description:
							$description = $v[$k2i . '.']['description'] ? $LANG->sL($v[$k2i . '.']['description'], 1) . '<br />' : '';

							// URL + onclick event:
							$onClickEvent = '';
							if (isset($v[$k2i . '.']['target']))
								$onClickEvent.="setTarget('" . $v[$k2i . '.']['target'] . "');";
							$v[$k2i . '.']['url'] = str_replace('###_URL###', $this->siteURL, $v[$k2i . '.']['url']);
							if (substr($v[$k2i . '.']['url'], 0, 7) == "http://" || substr($v[$k2i . '.']['url'], 0, 7) == 'mailto:') {
								$onClickEvent.="cur_href=unescape('" . rawurlencode($v[$k2i . '.']['url']) . "');link_current();";
							} else {
								$onClickEvent.="link_spec(unescape('" . $this->siteURL . rawurlencode($v[$k2i . '.']['url']) . "'));";
							}

							// Link:
							$A = array('<a href="#" onclick="' . htmlspecialchars($onClickEvent) . 'return false;">', '</a>');

							// Adding link to menu of user defined links:
							$subcats[$k2i] = '
								<tr>
									<td class="bgColor4">' . $A[0] . '<strong>' . htmlspecialchars($title) . ($this->curUrlInfo['info'] == $v[$k2i . '.']['url'] ? '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/blinkarrow_right.gif', 'width="5" height="9"') . ' class="c-blinkArrowR" alt="" />' : '') . '</strong><br />' . $description . $A[1] . '</td>
								</tr>';
						}
					}

					// Sort by keys:
					ksort($subcats);

					// Add menu to content:
					$content.= '

			<!--
				Special userdefined menu:
			-->
						<table border="0" cellpadding="1" cellspacing="1" id="typo3-linkSpecial">
							<tr>
								<td class="bgColor5" class="c-wCell" valign="top"><strong>' . $LANG->getLL('special', 1) . '</strong></td>
							</tr>
							' . implode('', $subcats) . '
						</table>
						';
				}
				break;
			case 'page':
				$pagetree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('rtePageTree');
				$pagetree->thisScript = $this->thisScript;
				$tree = $pagetree->getBrowsableTree();
				$cElements = $this->expandPage();
				$label = $GLOBALS['LANG']->getLL('contentRecords');
				$cElements = '<fieldset><legend>' . $label . '</legend><div style="overflow: auto; min-height: 200px;"><table style="width: 100%;"><tr><td>' . $cElements . '</td></tr></table></div></fieldset>';
				$content.= '

			<!--
				Wrapper table for page tree / record list:
			-->

					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
						<tr>
							<td valign="top" style="width: 58%;"><fieldset><legend>' . $GLOBALS['LANG']->getLL('pageTree') . '</legend><div style="overflow: auto;"><table style="width: 100%;"><tr><td>' . $tree . '</td></tr></table></div></fieldset></td>
							<td valign="top" style="width: 1%;"><img src="clear.gif" width="5" alt="" /></td>
							<td valign="top" style="width: 38%;">' . $cElements . '</td>
						</tr>
					</table>
					';
				break;
			default:
				//call hook
				foreach ($this->hookObjects as $hookObject) {
					$content .= $hookObject->getTab($this->act);
				}
				break;
		}

		$content.='</div>';
		// End page, return content:
		$content.= $this->doc->endPage();
		$content = $this->doc->insertStylesAndJS($content);
		return $content;
	}

	/**
	 * TYPO3 Element Browser: Showing a page tree and allows you to browse for records
	 *
	 * @return	string		HTML content for the module
	 */
	function main_db() {

		// Starting content:
		$content = $this->doc->startPage('TBE file selector');

		// Init variable:
		$pArr = explode('|', $this->bparams);

		// Making the browsable pagetree:
		$pagetree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TBE_PageTree');

		$pagetree->thisScript = $this->thisScript;
		$pagetree->ext_pArrPages = !strcmp($pArr[3], 'pages') ? 1 : 0;
		$pagetree->ext_showNavTitle = $GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.showNavTitle');
		$pagetree->addField('nav_title');

		$tree = $pagetree->getBrowsableTree();

		// Making the list of elements, if applicable:
		$cElements = $this->TBE_expandPage($pArr[3]);

		// Putting the things together, side by side:
		$content.= '

			<!--
				Wrapper table for page tree / record list:
			-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-EBrecords">
				<tr>
					<td class="c-wCell" valign="top">' . $this->barheader($GLOBALS['LANG']->getLL('pageTree') . ':') . $tree . '</td>
					<td class="c-wCell" valign="top">' . $cElements . '</td>
				</tr>
			</table>
			';

		// Add some space
		$content.='<br /><br />';

		// End page, return content:
		$content.= $this->doc->endPage();
		$content = $this->doc->insertStylesAndJS($content);
		return $content;
	}

	/**
	 * TYPO3 Element Browser: Showing a folder tree, allowing you to browse for files.
	 *
	 * @return	string		HTML content for the module
	 */
	function main_file() {
		global $BE_USER;

		// Starting content:
		$content.=$this->doc->startPage('TBE file selector');

		// Init variable:
		$pArr = explode('|', $this->bparams);

		// Create upload/create folder forms, if a path is given:
		$path = $this->expandFolder;
		if (!$path || !@is_dir($path)) {
			$path = $this->fileProcessor->findTempFolder() . '/'; // The closest TEMP-path is found
		}
		if ($path != '/' && @is_dir($path)) {
			$uploadForm = $this->uploadForm($path);
			$createFolder = $this->createFolder($path);
		} else {
			$createFolder = '';
			$uploadForm = '';
		}
		if ($BE_USER->getTSConfigVal('options.uploadFieldsInTopOfEB'))
			$content.=$uploadForm;

		// Getting flag for showing/not showing thumbnails:
		$noThumbs = $GLOBALS['BE_USER']->getTSConfigVal('options.noThumbsInEB');

		if (!$noThumbs) {
			// MENU-ITEMS, fetching the setting for thumbnails from File>List module:
			$_MOD_MENU = array('displayThumbs' => '');
			$_MCONF['name'] = 'file_list';
			$_MOD_SETTINGS = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleData($_MOD_MENU, \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('SET'), $_MCONF['name']);
			$addParams = '&act=' . $this->act . '&mode=' . $this->mode . '&expandFolder=' . rawurlencode($path) . '&bparams=' . rawurlencode($this->bparams);
			$thumbNailCheck = \TYPO3\CMS\Backend\Utility\BackendUtility::getFuncCheck('', 'SET[displayThumbs]', $_MOD_SETTINGS['displayThumbs'], 'browse_links.php', $addParams) . ' ' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_file_list.php:displayThumbs', 1);
		} else {
			$thumbNailCheck = '';
		}
		$noThumbs = $noThumbs ? $noThumbs : !$_MOD_SETTINGS['displayThumbs'];

		// Create folder tree:
		/** @var TBE_FolderTree $foldertree */
		$foldertree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TBE_FolderTree');
		$foldertree->thisScript = $this->thisScript;
		$foldertree->ext_noTempRecyclerDirs = ($this->mode == 'filedrag');
		$tree = $foldertree->getBrowsableTree();

		list(,, $specUid) = explode('_', $this->PM);

		if ($this->mode == 'filedrag') {
			$files = $this->TBE_dragNDrop($foldertree->specUIDmap[$specUid], $pArr[3]);
		} else {
			$files = $this->TBE_expandFolder($foldertree->specUIDmap[$specUid], $pArr[3], $noThumbs);
		}

		// Putting the parts together, side by side:
		$content.= '

			<!--
				Wrapper table for folder tree / file list:
			-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-EBfiles">
				<tr>
					<td class="c-wCell" valign="top">' . $this->barheader($GLOBALS['LANG']->getLL('folderTree') . ':') . $tree . '</td>
					<td class="c-wCell" valign="top">' . $files . '</td>
				</tr>
			</table>
			';
		$content.=$thumbNailCheck;

		// Adding create folder + upload forms if applicable:
		if (!$BE_USER->getTSConfigVal('options.uploadFieldsInTopOfEB'))
			$content.=$uploadForm;
		if ($BE_USER->isAdmin() || $BE_USER->getTSConfigVal('options.createFoldersInEB'))
			$content.=$createFolder;

		// Add some space
		$content.='<br /><br />';

		// Ending page, returning content:
		$content.= $this->doc->endPage();
		$content = $this->doc->insertStylesAndJS($content);
		return $content;
	}

	/**
	 * TYPO3 Element Browser: Showing a folder tree, allowing you to browse for folders.
	 *
	 * @return	string		HTML content for the module
	 */
	function main_folder() {
		global $BE_USER;

		// Starting content:
		$content = $this->doc->startPage('TBE folder selector');

		// Init variable:
		$parameters = explode('|', $this->bparams);

		// Create upload/create folder forms, if a path is given:
		$path = $this->expandFolder;
		if (!$path || !@is_dir($path)) {
			// The closest TEMP-path is found
			$path = $this->fileProcessor->findTempFolder() . '/';
		}
		if ($path != '/' && @is_dir($path)) {
			$createFolder = $this->createFolder($path);
		} else {
			$createFolder = '';
		}

		// Create folder tree:
		$foldertree = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TBE_FolderTree');
		$foldertree->thisScript = $this->thisScript;
		$foldertree->ext_noTempRecyclerDirs = ($this->mode == 'filedrag');
		$tree = $foldertree->getBrowsableTree(false);

		list(,, $specUid) = explode('_', $this->PM);

		if ($this->mode == 'filedrag') {
			$folders = $this->TBE_dragNDrop(
					$foldertree->specUIDmap[$specUid], $parameters[3]
			);
		} else {
			$folders = $this->TBE_expandSubFolders($foldertree->specUIDmap[$specUid]);
		}

		// Putting the parts together, side by side:
		$content.= '

			<!--
				Wrapper table for folder tree / folder list:
			-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-EBfiles">
				<tr>
					<td class="c-wCell" valign="top">' . $this->barheader($GLOBALS['LANG']->getLL('folderTree') . ':') . $tree . '</td>
					<td class="c-wCell" valign="top">' . $folders . '</td>
				</tr>
			</table>
			';

		// Adding create folder if applicable:
		if ($BE_USER->isAdmin() || $BE_USER->getTSConfigVal('options.createFoldersInEB')) {
			$content .= $createFolder;
		}

		// Add some space
		$content .= '<br /><br />';

		// Ending page, returning content:
		$content.= $this->doc->endPage();
		$content = $this->doc->insertStylesAndJS($content);

		return $content;
	}

	/*	 * ****************************************************************
	 *
	 * Record listing
	 *
	 * **************************************************************** */

	/**
	 * For RTE: This displays all content elements on a page and lets you create a link to the element.
	 *
	 * @return	string		HTML output. Returns content only if the ->expandPage value is set (pointing to a page uid to show tt_content records from ...)
	 */
	function expandPage() {
		global $BE_USER, $BACK_PATH;
		$out = '<table cellspacing="0" cellpadding="0" border="0" style="margin: 0pt; width: 100%;">';
		$expPageId = $this->expandPage;  // Set page id (if any) to expand
		// If there is an anchor value (content element reference) in the element reference, then force an ID to expand:
		if (!$this->expandPage && $this->curUrlInfo['cElement']) {
			$expPageId = $this->curUrlInfo['pageid']; // Set to the current link page id.
		}

		// Draw the record list IF there is a page id to expand:
		if ($expPageId && tx_tinymce_rte_base::testInt($expPageId) && $BE_USER->isInWebMount($expPageId)) {

			// Create header for listing, showing the page title/icon:
			$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
			$mainPageRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL('pages', $expPageId);
			$picon = \TYPO3\CMS\Backend\Utility\IconUtility::getIconImage('pages', $mainPageRec, $BACK_PATH, '');
			$picon.= htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($mainPageRec['title'], $titleLen));
			$out.=$picon . '<br />';

			$queries = array('tt_content.' => array('sorting' => 'colpos,sorting', 'select' => 'uid,header,hidden,starttime,endtime,fe_group,CType,colpos,bodytext'));
			if (is_array($this->thisConfig['linkhandler.'])) {
				foreach ($this->thisConfig['linkhandler.'] as $k => $v) {
					$tcaTable = substr($k, 0, -1);
					if (is_array($GLOBALS['TCA'][$tcaTable])) {
						if (is_array($this->thisConfig['linkhandler.'][$k][$this->expandPage . '.'])) {
							$queries = array_merge($queries, array($k => $this->thisConfig['linkhandler.'][$k][$this->expandPage . '.']));
						} else if (is_array($this->thisConfig['linkhandler.'][$k]['default.'])) {
							$queries = array_merge($queries, array($k => $this->thisConfig['linkhandler.'][$k]['default.']));
						}
					}
				}
			}

			foreach ($queries as $table => $query) {
				// set some mandatory default values
				if (!$query['sorting'])
					$query['sorting'] = $queries['tt_content.']['sorting'];
				if (!$query['select'])
					$query['select'] = $queries['tt_content.']['select'];

				$currentTable = substr($table, 0, strlen($table) - 1);

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						$query['select'], $currentTable, 'pid=' . intval($expPageId) .
						\TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($currentTable), '', $query['sorting']
				);
				$cc = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

				// Traverse list of records:
				$c = 0;
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$c++;
					$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getIconImage($currentTable, $row, $BACK_PATH, '');

					if ($this->curUrlInfo['act'] == 'page' && $this->curUrlInfo['cElement'] == $row['uid']) {
						$current = 'style="background: #b7bac0;"';
						//$arrCol='<img'.\TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH,'gfx/blinkarrow_left.gif','width="5" height="9"').' class="c-blinkArrowL" alt="" />';
					} else {
						$current = '';
						//$arrCol='';
					}
					// Putting list element HTML together:
					$cropAt = 24;
					$titleText = $row['header'] ? $row['header'] : (isset($row['bodytext']) ? strip_tags($row['bodytext']) : '');
					$t = strlen(htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($titleText, $titleLen))) > $cropAt ? '&nbsp;' . substr(htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($titleText, $titleLen)), 0, $cropAt) . '...' : '&nbsp;' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($titleText, $titleLen));

					$out.='<tr ' . $current . '><td style="width: 20px;"><img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/ol/join' . ($c == $cc ? 'bottom' : '') . '.gif', 'width="18" height="16"') . ' alt="" /></td>';

					$out .= '<td style="width: 18px;">' . $icon . '</td>';
					if( $currentTable == 'tt_content' && !$this->thisConfig['linkhandler.']['tt_content.']) {
						$out .= '<td><a href="#" onclick="return link_insert(\'' . $expPageId . '\',\'#' . $row['uid'] . '\');" title="' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($titleText, $titleLen)) . '">';
					} else {
						$out .= '<td><a href="#" onclick="return record_insert(\'' . $currentTable . '\',\'' . $row['uid'] . '\');" title="' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($titleText, $titleLen)) . '">';
					}

					$out .= $t . '</a></td></tr>';

					// Finding internal anchor points:
					if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList('text,textpic', $row['CType'])) {
						$split = preg_split('/(<a[^>]+name=[\'"]?([^"\'>[:space:]]+)[\'"]?[^>]*>)/i', $row['bodytext'], -1, PREG_SPLIT_DELIM_CAPTURE);

						foreach ($split as $skey => $sval) {
							if (($skey % 3) == 2) {
								// Putting list element HTML together:
								$sval = substr($sval, 0, 100);
								$out.='<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/ol/line.gif', 'width="18" height="16"') . ' alt="" />' .
										'<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/ol/join' . ($skey + 3 > count($split) ? 'bottom' : '') . '.gif', 'width="18" height="16"') . ' alt="" />' .
										'<a href="#" onclick="return link_insert(\'' . $expPageId . '\',\'#' . rawurlencode($sval) . '\');">' .
										htmlspecialchars(' <A> ' . $sval) .
										'</a><br />';
							}
						}
					}
				}
			} //end of queries
		}
		$out .= '</table>';
		return $out;
	}

	/**
	 * For TYPO3 Element Browser: This lists all content elements from the given list of tables
	 *
	 * @param	string		Commalist of tables. Set to "*" if you want all tables.
	 * @return	string		HTML output.
	 */
	function TBE_expandPage($tables) {
		global $TCA, $BE_USER, $BACK_PATH;
		$out = '';
		if ($this->expandPage >= 0 && tx_tinymce_rte_base::testInt($this->expandPage) && $BE_USER->isInWebMount($this->expandPage)) {

			// Set array with table names to list:
			if (!strcmp(trim($tables), '*')) {
				$tablesArr = array_keys($TCA);
			} else {
				$tablesArr = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tables, 1);
			}
			reset($tablesArr);

			// Headline for selecting records:
			$out.=$this->barheader($GLOBALS['LANG']->getLL('selectRecords') . ':');

			// Create the header, showing the current page for which the listing is. Includes link to the page itself, if pages are amount allowed tables.
			$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
			$mainPageRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $this->expandPage);
			$ATag = '';
			$ATag_e = '';
			$ATag2 = '';
			if (in_array('pages', $tablesArr)) {
				$ficon = $BACK_PATH . \TYPO3\CMS\Backend\Utility\IconUtility::getIcon('pages', $mainPageRec);
				$ATag = "<a href=\"#\" onclick=\"return insertElement('pages', '" . $mainPageRec['uid'] . "', 'db', unescape('" . rawurlencode($mainPageRec['title']) . "'), '', '', '" . $ficon . "','',1);\">";
				$ATag2 = "<a href=\"#\" onclick=\"return insertElement('pages', '" . $mainPageRec['uid'] . "', 'db', unescape('" . rawurlencode($mainPageRec['title']) . "'), '', '', '" . $ficon . "','',0);\">";
				$ATag_alt = substr($ATag, 0, -4) . ",'',1);\">";
				$ATag_e = '</a>';
			}
			$picon = $BACK_PATH . \TYPO3\CMS\Backend\Utility\IconUtility::getIconImage('pages', $mainPageRec, $BACK_PATH, '');
			$pBicon = $ATag2 ? '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/plusbullet2.gif', 'width="18" height="16"') . ' alt="" />' : '';
			$pText = htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($mainPageRec['title'], $titleLen));
			$out.=$picon . $ATag2 . $pBicon . $ATag_e . $ATag . $pText . $ATag_e . '<br />';

			// Initialize the record listing:
			$id = $this->expandPage;
			$pointer = \TYPO3\CMS\Core\Utility\MathUtility::isIntegerInRange($this->pointer,0,100000);
			$perms_clause = $GLOBALS['BE_USER']->getPagePermsClause(1);
			$pageinfo = \TYPO3\CMS\Backend\Utility\BackendUtility::readPageAccess($id, $perms_clause);
			$table = '';

			// Generate the record list:
			$dblist = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TBE_browser_recordList');
			$dblist->thisScript = $this->thisScript;
			$dblist->backPath = $GLOBALS['BACK_PATH'];
			$dblist->thumbs = 0;
			$dblist->calcPerms = $GLOBALS['BE_USER']->calcPerms($pageinfo);
			$dblist->noControlPanels = 1;
			$dblist->clickMenuEnabled = 0;
			$dblist->tableList = implode(',', $tablesArr);

			$dblist->start($id, \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('table'), $pointer, \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('search_field'), \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('search_levels'), \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('showLimit')
			);
			$dblist->setDispFields();
			$dblist->generateList($id, $table);
			$dblist->writeBottom();

			//	Add the HTML for the record list to output variable:
			$out.=$dblist->HTMLcode;
			// Add support for fieldselectbox in singleTableMode
			if ($dblist->table) {
				$out.= $dblist->fieldSelectBox($dblist->table);
			}
			$out.=$dblist->getSearchBox();
		}

		// Return accumulated content:
		return $out;
	}

	/**
	 * Render list of folders inside a folder.
	 *
	 * @param	string		string of the current folder
	 * @return	string		HTML output
	 */
	function TBE_expandSubFolders($expandFolder = 0) {
		$content = '';

		$expandFolder = $expandFolder ?
				$expandFolder :
				$this->expandFolder;

		if ($expandFolder && $this->checkFolder($expandFolder)) {
			if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($expandFolder, PATH_site)) {
				$rootFolder = substr($expandFolder, strlen(PATH_site));
			}

			$folders = array();

			// Listing the folders:
			$folders = \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs($expandFolder);
			if (count($folders) > 0) {
				foreach ($folders as $index => $folder) {
					$folders[$index] = $rootFolder . $folder . '/';
				}
			}
			$content.= $this->folderList($rootFolder, $folders);
		}

		// Return accumulated content for folderlisting:
		return $content;
	}

	/*	 * ****************************************************************
	 *
	 * File listing
	 *
	 * **************************************************************** */

	/**
	 * For RTE: This displays all files from folder. No thumbnails shown
	 *
	 * @param string $folder The folder path to expand
	 * @param string $extensionList List of fileextensions to show
	 * @return string HTML output
	 * @todo Define visibility
	 */
	public function expandFolder(\TYPO3\CMS\Core\Resource\Folder $folder, $extensionList = '') {
		$out = '';
		$renderFolders = $this->act === 'folder';
		if ($folder->checkActionPermission('read')) {
			// Prepare current path value for comparison (showing red arrow)
			$currentIdentifier = '';
			if ($this->curUrlInfo['value']) {
				$currentIdentifier = $this->curUrlInfo['info'];
			}
			// Create header element; The folder from which files are listed.
			$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
			$folderIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile('folder');
			$folderIcon .= htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($folder->getIdentifier(), $titleLen));
			#$picon = '<a href="#" onclick="return link_folder(\'file:' . $folder->getCombinedIdentifier() . '\');">' . $folderIcon . '</a>';

			$picon = '<a href="#" onclick="return link_insert(\'' . $folder->getCombinedIdentifier() . '\');">' . $folderIcon . '</a>';
			if ($this->curUrlInfo['act'] == 'folder' && $currentIdentifier == $folder->getCombinedIdentifier()) {
				$out .= '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/blinkarrow_left.gif', 'width="5" height="9"') . ' class="c-blinkArrowL" alt="" />';
			}
			$out .= $picon . '<br />';
			// Get files from the folder:
			if ($renderFolders) {
				$items = $folder->getSubfolders();
			} else {
				$filter = new \TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter();
				$filter->setAllowedFileExtensions($extensionList);
				$folder->getStorage()->setFileAndFolderNameFilters(array(array($filter, 'filterFileList')));

				$items = $folder->getFiles();
			}
			$c = 0;
			$totalItems = count($items);
			foreach ($items as $fileOrFolderObject) {
				$c++;
				if ($renderFolders) {
					$fileIdentifier = $fileOrFolderObject->getCombinedIdentifier();
					$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile('folder');
					$itemUid = 'file:' . $fileIdentifier;
				} else {
					$fileIdentifier = $fileOrFolderObject->getUid();
					// File icon:
					$fileExtension = $fileOrFolderObject->getExtension();
					// Get size and icon:
					$size = ' (' . \TYPO3\CMS\Core\Utility\GeneralUtility::formatSize($fileOrFolderObject->getSize()) . 'bytes)';
					$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile($fileExtension, array('title' => $fileOrFolderObject->getName() . $size));
					$itemUid = 'file:' . $fileIdentifier;
				}
				// If the listed file turns out to be the CURRENT file, then show blinking arrow:
				if (($this->curUrlInfo['act'] == 'file' || $this->curUrlInfo['act'] == 'folder') && $currentIdentifier == $fileIdentifier) {
					$arrCol = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/blinkarrow_left.gif', 'width="5" height="9"') . ' class="c-blinkArrowL" alt="" />';
				} else {
					$arrCol = '';
				}
				// Put it all together for the file element:
				$out .= '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], ('gfx/ol/join' . ($c == $totalItems ? 'bottom' : '') . '.gif'), 'width="18" height="16"') . ' alt="" />' . $arrCol . '<a href="#" onclick="return link_insert(\'' . $itemUid . '\');">' . $icon . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($fileOrFolderObject->getName(), $titleLen)) . '</a><br />';
			}
		}
		return $out;
	}

	/**
	 * For TYPO3 Element Browser: Expand folder of files.
	 *
	 * @param	string		The folder path to expand
	 * @param	string		List of fileextensions to show
	 * @param	boolean		Whether to show thumbnails or not. If set, no thumbnails are shown.
	 * @return	string		HTML output
	 */
	function TBE_expandFolder($expandFolder = 0, $extensionList = '', $noThumbs = 0) {
		global $LANG;

		$expandFolder = $expandFolder ? $expandFolder : $this->expandFolder;
		$out = '';
		if ($expandFolder && $this->checkFolder($expandFolder)) {
			// Listing the files:
			$files = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir($expandFolder, $extensionList, 1, 1); // $extensionList="",$prependPath=0,$order='')
			$out.= $this->fileList($files, $expandFolder, $noThumbs);
		}

		// Return accumulated content for filelisting:
		return $out;
	}

	/**
	 * For RTE: This displays all IMAGES (gif,png,jpg) (from extensionList) from folder. Thumbnails are shown for images.
	 * This listing is of images located in the web-accessible paths ONLY - the listing is for drag-n-drop use in the RTE
	 *
	 * @param	string		The folder path to expand
	 * @param	string		List of fileextensions to show
	 * @return	string		HTML output
	 */
	function TBE_dragNDrop($expandFolder = 0, $extensionList = '') {
		global $BACK_PATH;
		$expandFolder = $expandFolder ? $expandFolder : $this->expandFolder;
		$out = '';
		if ($expandFolder && $this->checkFolder($expandFolder)) {
			if ($this->isWebFolder($expandFolder)) {

				// Read files from directory:
				$files = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir($expandFolder, $extensionList, 1, 1); // $extensionList="",$prependPath=0,$order='')
				if (is_array($files)) {
					$out.=$this->barheader(sprintf($GLOBALS['LANG']->getLL('files') . ' (%s):', count($files)));

					$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
					$picon = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/i/_icon_webfolders.gif', 'width="18" height="16"') . ' alt="" />';
					$picon.=htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(basename($expandFolder), $titleLen));
					$out.=$picon . '<br />';

					// Init row-array:
					$lines = array();

					// Add "drag-n-drop" message:
					$lines[] = '
						<tr>
							<td colspan="2">' . $this->getMsgBox($GLOBALS['LANG']->getLL('findDragDrop')) . '</td>
						</tr>';

					// Traverse files:
					while (list(, $filepath) = each($files)) {
						$fI = pathinfo($filepath);

						// URL of image:
						$iurl = $this->siteURL . \TYPO3\CMS\Core\Utility\GeneralUtility::rawurlencodeFP(substr($filepath, strlen(PATH_site)));

						// Show only web-images
						if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList('gif,jpeg,jpg,png', $fI['extension'])) {
							$imgInfo = @getimagesize($filepath);
							$pDim = $imgInfo[0] . 'x' . $imgInfo[1] . ' pixels';

							$ficon = \TYPO3\CMS\Backend\Utility\BackendUtility::getFileIcon(strtolower($fI['extension']));
							$size = ' (' . \TYPO3\CMS\Core\Utility\GeneralUtility::formatSize(filesize($filepath)) . 'bytes' . ($pDim ? ', ' . $pDim : '') . ')';
							$icon = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/fileicons/' . $ficon, 'width="18" height="16"') . ' class="absmiddle" title="' . htmlspecialchars($fI['basename'] . $size) . '" alt="" />';
							$filenameAndIcon = $icon . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(basename($filepath), $titleLen));

							if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('noLimit')) {
								$maxW = 10000;
								$maxH = 10000;
							} else {
								$maxW = 380;
								$maxH = 500;
							}
							$IW = $imgInfo[0];
							$IH = $imgInfo[1];
							if ($IW > $maxW) {
								$IH = ceil($IH / $IW * $maxW);
								$IW = $maxW;
							}
							if ($IH > $maxH) {
								$IW = ceil($IW / $IH * $maxH);
								$IH = $maxH;
							}

							// Make row:
							$lines[] = '
								<tr>
									<td nowrap="nowrap">' . $filenameAndIcon . '&nbsp;</td>
									<td nowrap="nowrap">' .
									($imgInfo[0] != $IW ? '<a href="' . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::linkThisScript(array('noLimit' => '1'))) . '">' .
											'<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/icon_warning2.gif', 'width="18" height="16"') . ' title="' . $GLOBALS['LANG']->getLL('clickToRedrawFullSize', 1) . '" alt="" />' .
											'</a>' : '') .
									$pDim . '&nbsp;</td>
								</tr>';

							$lines[] = '
								<tr>
									<td colspan="2"><img src="' . $iurl . '" width="' . $IW . '" height="' . $IH . '" border="1" alt="" /></td>
								</tr>';
							$lines[] = '
								<tr>
									<td colspan="2"><img src="clear.gif" width="1" height="3" alt="" /></td>
								</tr>';
						}
					}

					// Finally, wrap all rows in a table tag:
					$out.='


			<!--
				File listing / Drag-n-drop
			-->
						<table border="0" cellpadding="0" cellspacing="1" id="typo3-dragBox">
							' . implode('', $lines) . '
						</table>';
				}
			} else {
				// Print this warning if the folder is NOT a web folder:
				$out.=$this->barheader($GLOBALS['LANG']->getLL('files'));
				$out.=$this->getMsgBox($GLOBALS['LANG']->getLL('noWebFolder'), 'icon_warning2');
			}
		}
		return $out;
	}

	/*	 * ****************************************************************
	 *
	 * Miscellaneous functions
	 *
	 * **************************************************************** */

	/**	 * Prints a 'header' where string is in a tablecell
	 *
	 * @param	string		The string to print in the header. The value is htmlspecialchars()'ed before output.
	 * @return	string		The header HTML (wrapped in a table)
	 */
	function barheader($str) {
		return '

			<!--
				Bar header:
			-->
			<h3>' . htmlspecialchars($str) . '</h3>
			';
	}

	/**
	 * Displays a message box with the input message
	 *
	 * @param	string		Input message to show (will be htmlspecialchars()'ed inside of this function)
	 * @param	string		Icon filename body from gfx/ (default is "icon_note") - meant to allow change to warning type icons...
	 * @return	string		HTML for the message (wrapped in a table).
	 */
	function getMsgBox($in_msg, $icon = 'icon_note') {
		global $BACK_PATH;
		$msg = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/' . $icon . '.gif', 'width="18" height="16"') . ' alt="" />' . htmlspecialchars($in_msg);
		$msg = '

			<!--
				Message box:
			-->
			<table cellspacing="0" id="typo3-msgBox">
				<tr>
					<td>' . $msg . '</td>
				</tr>
			</table>
			';
		return $msg;
	}

	/**
	 * For RTE/link: This prints the 'currentUrl'
	 *
	 * @param	string		URL value.  The value is htmlspecialchars()'ed before output.
	 * @return	string		HTML content, wrapped in a table.
	 */
	function printCurrentUrl($str) {
		return '';
		return '

			<!--
				Print current URL
			-->
			<fieldset>
			  <legend>' . $GLOBALS['LANG']->getLL('currentLink', 1) . '</legend>
			    ' . htmlspecialchars(rawurldecode($str)) . '
			</fieldset><img src="clear.gif" height="8" width="100%" alt="" />';
	}

	/**
	 * For RTE/link: Parses the incoming URL and determines if it's a page, file, external or mail address.
	 *
	 * @param	string		HREF value tp analyse
	 * @param	string		The URL of the current website (frontend)
	 * @return	array		Array with URL information stored in assoc. keys: value, act (page, file, spec, mail), pageid, cElement, info
	 */
	function parseCurUrl($href, $siteUrl) {
		$href = trim($href);
		if ($href) {
			$info = array();

			// Default is "url":
			$info['value'] = $href;
			$info['act'] = 'url';

			$specialParts = explode('#_SPECIAL', $href);
			if (count($specialParts) == 2) { // Special kind (Something RTE specific: User configurable links through: "userLinks." from ->thisConfig)
				$info['value'] = '#_SPECIAL' . $specialParts[1];
				$info['act'] = 'spec';
			} elseif (strpos($href, 'file:') !== FALSE) {
				$rel = substr($href, strpos($href, 'file:') + 5);
				$rel = rawurldecode($rel);
				// resolve FAL-api "file:UID-of-sys_file-record" and "file:combined-identifier"
				$fileOrFolderObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->retrieveFileOrFolderObject($rel);
				if ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\Folder) {
					$info['act'] = 'folder';
					$info['value'] = $fileOrFolderObject->getCombinedIdentifier();
				} elseif ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\FileInterface) {
					$info['act'] = 'file';
					$info['value'] = $fileOrFolderObject->getUid();
				} else {
					$info['value'] = $rel;
				}
			} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($href, $siteUrl)) { // If URL is on the current frontend website:
				$rel = substr($href, strlen($siteUrl));
				if (@file_exists(PATH_site . rawurldecode($rel))) { // URL is a file, which exists:
					$info['value'] = rawurldecode($rel);
					if (@is_dir(PATH_site . $info['value'])) {
						$info['act'] = 'folder';
					} else {
						$info['act'] = 'file';
					}
				} else { // URL is a page (id parameter)
					$uP = parse_url($rel);
					if (!trim($uP['path'])) {
						$pp = explode('id=', $uP['query']);
						$id = $pp[1];
						if ($id) {
							// Checking if the id-parameter is an alias.
							if (!tx_tinymce_rte_base::testInt($id)) {
								list($idPartR) = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordsByField('pages', 'alias', $id);
								$id = intval($idPartR['uid']);
							}

							$pageRow = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL('pages', $id);
							$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
							$info['value'] = $GLOBALS['LANG']->getLL('page', 1) . " '" . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($pageRow['title'], $titleLen)) . "' (ID:" . $id . ($uP['fragment'] ? ', #' . $uP['fragment'] : '') . ')';
							$info['pageid'] = $id;
							$info['cElement'] = $uP['fragment'];
							$info['act'] = 'page';
						}
					}
				}
			} else { // Email link:
				if (strtolower(substr($href, 0, 7)) == 'mailto:') {
					$info['value'] = trim(substr($href, 7));
					$info['act'] = 'mail';
				}
			}
			$info['info'] = $info['value'];
		} else { // NO value inputted:
			$info = array();
			$info['info'] = $GLOBALS['LANG']->getLL('none');
			$info['value'] = '';
			$info['act'] = 'page';
		}
		// let the hook have a look
		foreach ($this->hookObjects as $hookObject) {
			$info = $hookObject->parseCurrentUrl($href, $siteUrl, $info);
		}
		return $info;
	}

	/**
	 * For TBE: Makes an upload form for uploading files to the filemount the user is browsing.
	 * The files are uploaded to the tce_file.php script in the core which will handle the upload.
	 *
	 * @param	string		Absolute filepath on server to which to upload.
	 * @return	string		HTML for an upload form.
	 */
	function uploadForm($path) {
		global $BACK_PATH;
		$count = 3;
		if ($this->isReadOnlyFolder($path))
			return '';

		// Create header, showing upload path:
		$header = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($path, PATH_site) ? substr($path, strlen(PATH_site)) : $path;
		$code = $this->barheader($GLOBALS['LANG']->getLL('uploadImage') . ':');
		$code.='

			<!--
				Form, for uploading files:
			-->
			<form action="tce_file.php" method="post" name="editform" enctype="' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['form_enctype'] . '">
				<table border="0" cellpadding="0" cellspacing="3" id="typo3-uplFiles">
					<tr>
						<td><strong>' . $GLOBALS['LANG']->getLL('path', 1) . ':</strong> ' . htmlspecialchars($header) . '</td>
					</tr>
					<tr>
						<td>';

		// Traverse the number of upload fields (default is 3):
		for ($a = 1; $a <= $count; $a++) {
			$code.='<input type="file" name="upload_' . $a . '"' . $this->doc->formWidth(35) . ' size="50" />
				<input type="hidden" name="file[upload][' . $a . '][target]" value="' . htmlspecialchars($path) . '" />
				<input type="hidden" name="file[upload][' . $a . '][data]" value="' . $a . '" /><br />';
		}

		// Make footer of upload form, including the submit button:
		$redirectValue = 'browse_links.php?act=' . $this->act . '&mode=' . $this->mode . '&expandFolder=' . rawurlencode($path) . '&bparams=' . rawurlencode($this->bparams);
		$code.='<input type="hidden" name="redirect" value="' . htmlspecialchars($redirectValue) . '" />' .
				'<input type="submit" name="submit" value="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:file_upload.php.submit', 1) . '" />';

		$code.='
			<div id="c-override">
				<input type="checkbox" name="overwriteExistingFiles" value="1" /> ' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_misc.xml:overwriteExistingFiles', 1) . '
			</div>
		';


		$code.='</td>
					</tr>
				</table>
			</form>';

		return $code;
	}

	/**
	 * For TBE: Makes a form for creating new folders in the filemount the user is browsing.
	 * The folder creation request is sent to the tce_file.php script in the core which will handle the creation.
	 *
	 * @param	string		Absolute filepath on server in which to create the new folder.
	 * @return	string		HTML for the create folder form.
	 */
	function createFolder($path) {
		global $BACK_PATH;

		if ($this->isReadOnlyFolder($path))
			return '';
		// Create header, showing upload path:
		$header = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($path, PATH_site) ? substr($path, strlen(PATH_site)) : $path;
		$code = $this->barheader($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:file_newfolder.php.pagetitle') . ':');
		$code.='

			<!--
				Form, for creating new folders:
			-->
			<form action="tce_file.php" method="post" name="editform2">
				<table border="0" cellpadding="0" cellspacing="3" id="typo3-crFolder">
					<tr>
						<td><strong>' . $GLOBALS['LANG']->getLL('path', 1) . ':</strong> ' . htmlspecialchars($header) . '</td>
					</tr>
					<tr>
						<td>';

		// Create the new-folder name field:
		$a = 1;
		$code.='<input' . $this->doc->formWidth(20) . ' type="text" name="file[newfolder][' . $a . '][data]" />' .
				'<input type="hidden" name="file[newfolder][' . $a . '][target]" value="' . htmlspecialchars($path) . '" />';

		// Make footer of upload form, including the submit button:
		$redirectValue = 'browse_links.php?act=' . $this->act . '&mode=' . $this->mode . '&expandFolder=' . rawurlencode($path) . '&bparams=' . rawurlencode($this->bparams);
		$code.='<input type="hidden" name="redirect" value="' . htmlspecialchars($redirectValue) . '" />' .
				'<input type="submit" name="submit" value="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:file_newfolder.php.submit', 1) . '" />';

		$code.='</td>
					</tr>
				</table>
			</form>';

		return $code;
	}

	/**
	 * Get the HTML data required for a bulk selection of files of the TYPO3 Element Browser.
	 *
	 * @param	integer		$filesCount: Number of files currently displayed
	 * @return	string		HTML data required for a bulk selection of files - if $filesCount is 0, nothing is returned
	 */
	function getBulkSelector($filesCount) {
		global $BACK_PATH;
		$out = '';

		if ($filesCount) {
			$labelToggleSelection = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_browse_links.php:toggleSelection', 1);
			$labelImportSelection = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_browse_links.php:importSelection', 1);

			$out = $this->doc->spacer(15) . '<div>' .
					'<a href="#" onclick="BrowseLinks.Selector.toggle()">' .
					'<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/clip_select.gif', 'width="12" height="12"') . ' title="' . $labelToggleSelection . '" alt="" /> ' .
					$labelToggleSelection . '</a>' . $this->doc->spacer(5) .
					'<a href="#" onclick="BrowseLinks.Selector.handle()">' .
					'<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH, 'gfx/import.gif', 'width="12" height="12"') . ' title="' . $labelImportSelection . '" alt="" /> ' .
					$labelImportSelection . '</a>' .
					'</div>' . $this->doc->spacer(15);
		}
		return $out;
	}

}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce_rte/mod1/browse_links.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce_rte/mod1/browse_links.php']);
}


// Make instance:
$SOBE = new SC_browse_links();
$SOBE->extended();
?>
