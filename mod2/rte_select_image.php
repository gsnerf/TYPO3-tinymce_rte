<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 1999-2004 Kasper Skaarhoj (kasperYYYY@typo3.com)
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
 * Displays image selector for the RTE
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
unset($MCONF);
require ('conf.php');
require ($BACK_PATH . 'init.php');
require_once ($BACK_PATH . 'template.php');

require_once (PATH_t3lib . 'class.t3lib_foldertree.php');
require_once (PATH_t3lib . 'class.t3lib_stdgraphic.php');
require_once (PATH_t3lib . 'class.t3lib_basicfilefunc.php');
//$LANG->includeLLFile('EXT:tinymce_rte/mod2/locallang_rte_select_image.php');
$LANG->includeLLFile('EXT:tinymce_rte/mod2/locallang_rte_select_image.xml');

use \TYPO3\CMS\Core\Utility\GeneralUtility as GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtility;

/**
 * Local Folder Tree
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage tx_rte
 */
class localFolderTree extends \TYPO3\CMS\Backend\Tree\View\FolderTreeView {

	var $ext_IconMode = 1;

	/**
	 * Initializes the script path
	 *
	 * @return	void
	 */
	function __construct() {
		$this->thisScript = GeneralUtility::getIndpEnv('SCRIPT_NAME');
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
	public function PM_ATagWrap($icon, $cmd, $bMark = '') {
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
 * Script Class
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage tx_rte
 */
class SC_rte_select_image {

	var $content;
	var $siteUrl;
	var $act;
	var $modData;
	var $thisConfig;
	var $thisScript;
	var $allowedItems;
	var $doc;
	var $imgPath;

	/**
	 * Pre-initialization - the point is to do some processing before the actual init() function; In between we might have some magic-image processing going on...
	 *
	 * @return	[type]		...
	 */
	function preinit() {
		global $BE_USER;

		// Current site url:
		$this->siteUrl = GeneralUtility::getIndpEnv("TYPO3_SITE_URL");

		// Determine nature of current url:
		$this->act = GeneralUtility::_GP("act");

		$this->modData = $BE_USER->getModuleData("rte_select_image.php", "ses");
		if ($this->act != "image") {
			if (isset($this->act)) {
				$this->modData["act"] = $this->act;
				$BE_USER->pushModuleData("rte_select_image.php", $this->modData);
			} else {
				$this->act = $this->modData["act"];
			}
		}

		$expandPage = GeneralUtility::_GP("expandFolder");
		if (isset($expandPage)) {
			$this->modData["expandFolder"] = $expandPage;
			$BE_USER->pushModuleData("rte_select_image.php", $this->modData);
		} else {
			GeneralUtility::_GETset($this->modData["expandFolder"], 'expandFolder');
		}

		if (!$this->act) {
			$this->act = "magic";
		}

		$RTEtsConfigParts = explode(":", GeneralUtility::_GP("RTEtsConfigParams"));
		if (count($RTEtsConfigParts) < 2) {
			die("Error: The GET parameter 'RTEtsConfigParams' was missing. Close the window.");
		}
		$RTEsetup = $GLOBALS["BE_USER"]->getTSConfig("RTE", BackendUtility::getPagesTSconfig($RTEtsConfigParts[5]));
		$this->thisConfig = BackendUtility::RTEsetup($RTEsetup["properties"], $RTEtsConfigParts[0], $RTEtsConfigParts[2], $RTEtsConfigParts[4]);
		$this->thisScript = GeneralUtility::getIndpEnv('SCRIPT_NAME');
		$this->imgPath = $RTEtsConfigParts[6];

		$this->allowedItems = array_diff(explode(",", "magic,plain,upload"), GeneralUtility::trimExplode(",", $this->thisConfig["blindImageOptions"], 1));
		reset($this->allowedItems);
		if (!in_array($this->act, $this->allowedItems)) {
			$this->act = current($this->allowedItems);
		}

		// Setting alternative browsing mounts (ONLY local to browse_links.php this script so they stay "read-only")
		$altMountPoints = trim($GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.altElementBrowserMountPoints'));
		if ($altMountPoints) {
			$GLOBALS['BE_USER']->groupData['webmounts'] = implode(',', array_unique(\TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $altMountPoints)));
			$GLOBALS['WEBMOUNTS'] = $GLOBALS['BE_USER']->returnWebmounts();
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function rteImageStorageDir() {
		$dir = $this->imgPath ? $this->imgPath : $GLOBALS["TYPO3_CONF_VARS"]["BE"]["RTE_imageStorageDir"];
		;
		return $dir;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function magicProcess() {
		if ($this->act == "magic" && GeneralUtility::_GP("insertMagicImage")) {
			$filepath = GeneralUtility::_GP("insertMagicImage");

			$imgObj = GeneralUtility::makeInstance("t3lib_stdGraphic");
			$imgObj->init();
			$imgObj->mayScaleUp = 0;
			$imgObj->tempPath = PATH_site . $imgObj->tempPath;

			$imgInfo = $imgObj->getImageDimensions($filepath);

			if (is_array($imgInfo) && count($imgInfo) == 4 && $this->rteImageStorageDir()) {
				$fI = pathinfo($imgInfo[3]);
				$fileFunc = GeneralUtility::makeInstance("t3lib_basicFileFunctions");
				$basename = $fileFunc->cleanFileName("RTEmagicP_" . $fI["basename"]);
				$destPath = PATH_site . $this->rteImageStorageDir();
				if (@is_dir($destPath)) {
					$destName = $fileFunc->getUniqueName($basename, $destPath);
					@copy($imgInfo[3], $destName);

					$cHeight = t3lib_utility_Math::forceIntegerInRange(GeneralUtility::_GP("cHeight"), 0, $this->thisConfig['typo3filemanager.']['maxMagicImages.']['height']);
					$cWidth = t3lib_utility_Math::forceIntegerInRange(GeneralUtility::_GP("cWidth"), 0, $this->thisConfig['typo3filemanager.']['maxMagicImages.']['width']);
					if (!$cHeight)
						$cHeight = $this->thisConfig['typo3filemanager.']['maxMagicImages.']['height'];
					if (!$cWidth)
						$cWidth = $this->thisConfig['typo3filemanager.']['maxMagicImages.']['width'];
					//			debug(array($cHeight,$cWidth));
					//exit;
					$imgI = $imgObj->imageMagickConvert($filepath, "WEB", $cWidth . "m", $cHeight . "m"); // ($imagefile,$newExt,$w,$h,$params,$frame,$options,$mustCreate=0)
					//		debug($imgI);
					if ($imgI[3]) {
						$fI = pathinfo($imgI[3]);
						$mainBase = "RTEmagicC_" . substr(basename($destName), 10) . "." . $fI["extension"];
						$destName = $fileFunc->getUniqueName($mainBase, $destPath);
						@copy($imgI[3], $destName);

						$iurl = substr($destName, strlen(PATH_site));
						echo'
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>TYPO3 Imagebrowser</title>
</head>
<script language="javascript" type="text/javascript" src="../res/tiny_mce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript">
	function insertImage(file,width,height)	{
		var win = tinyMCEPopup.getWindowArg("window");
		if (win) {
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = file;
			// for image browsers: update image dimensions
			if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
			if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(file);
		} else {
			tinyMCEPopup.execCommand("mceBeginUndoLevel");
			var ed = tinyMCE.activeEditor;
			var el = ed.selection.getNode();
			var args = {
				"title" : "",
				"src" : file,
				"width" : width,
				"height" : height
			};

			if (el && el.nodeName == "IMG") {
				ed.dom.setAttribs(el, args);
			} else {
				ed.execCommand("mceInsertContent", false, \'<img id="__mce_tmp" />\', {skip_undo : 1});
				ed.dom.setAttribs("__mce_tmp", args);
				ed.dom.setAttrib("__mce_tmp", "id", "");
				ed.undoManager.add();
			}
			tinyMCEPopup.execCommand("mceEndUndoLevel");
		}

		tinyMCEPopup.close();

		return false;
	}
</script>
<body>
<script language="javascript" type="text/javascript">
	insertImage(\'' . $iurl . '\',' . $imgI[0] . ',' . $imgI[1] . ');
</script>
</body>
</html>';
					}
				}
			}
			exit;
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function init() {
		global $LANG, $BACK_PATH;

		$this->doc = GeneralUtility::makeInstance('template');
		$this->doc->docType = 'xhtml_trans';
		$this->doc->backPath = $BACK_PATH;

		$this->doc->JScode = '
		<script language="javascript" type="text/javascript" src="../res/tiny_mce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript">
			<!--
			function jumpToUrl(URL,anchor)	{	//
				var add_act = URL.indexOf("act=")==-1 ? "&act=' . $this->act . '" : "";
				var RTEtsConfigParams = "&RTEtsConfigParams=' . rawurlencode(GeneralUtility::_GP('RTEtsConfigParams')) . '";

				var cur_width = selectedImageRef ? "&cWidth="+selectedImageRef.width : "";
				var cur_height = selectedImageRef ? "&cHeight="+selectedImageRef.height : "";

				//var theLocation = URL+add_act+RTEtsConfigParams+cur_width+cur_height+(anchor?anchor:"");
				var theLocation = URL+add_act+RTEtsConfigParams+cur_width+cur_height;
				document.location = theLocation;
				return false;
			}
			function insertImage(file,width,height)	{
				var win = tinyMCEPopup.getWindowArg("window");
				if (win) {
					win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = file;
					// for image browsers: update image dimensions
					if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
					if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(file);
				} else {
					tinyMCEPopup.execCommand("mceBeginUndoLevel");
					var ed = tinyMCE.activeEditor;
					var el = ed.selection.getNode();
					var args = {
						"title" : "",
						"src" : file,
						"width" : width,
						"height" : height
					};

					if (el && el.nodeName == "IMG") {
						ed.dom.setAttribs(el, args);
					} else {
						ed.execCommand("mceInsertContent", false, \'<img id="__mce_tmp" />\', {skip_undo : 1});
						ed.dom.setAttribs("__mce_tmp", args);
						ed.dom.setAttrib("__mce_tmp", "id", "");
						ed.undoManager.add();
					}
					tinyMCEPopup.execCommand("mceEndUndoLevel");
				}

				tinyMCEPopup.close();

				return false;
			}
            
			function launchView(url)	{	//
				var thePreviewWindow="";
				thePreviewWindow = window.open("' . $this->siteUrl . TYPO3_mainDir . 'show_item.php?table="+url,"ShowItem","height=300,width=410,status=0,menubar=0,resizable=0,location=0,directories=0,scrollbars=1,toolbar=0");
				if (thePreviewWindow && thePreviewWindow.focus)	{
					thePreviewWindow.focus();
				}
			}
			function getCurrentImageRef()	{	//
				if (self.parent.parent
				&& self.parent.parent.document.idPopup
				&& self.parent.parent.document.idPopup.document
				&& self.parent.parent.document.idPopup.document._selectedImage)	{
					return self.parent.parent.document.idPopup.document._selectedImage;
				}
				return "";
			}
			function printCurrentImageOptions()	{	//
				var styleSelector=\'<select name="iClass" style="width:140px;"><option value=""></option><option value="TestClass">TestClass</option></select>\';
				var alignSelector=\'<select name="iAlign" style="width:60px;"><option value=""></option><option value="left">Left</option><option value="right">Right</option></select>\';
				var bgColor=\' class="bgColor4"\';
				var sz="";
				sz+=\'<table border=0 cellpadding=1 cellspacing=1><form action="" name="imageData">\';
				sz+=\'<tr><td\'+bgColor+\'>' . $LANG->getLL("width") . ': <input type="text" name="iWidth" value=""' . $GLOBALS["TBE_TEMPLATE"]->formWidth(4) . '>&nbsp;&nbsp;' . $LANG->getLL("height") . ': <input type="text" name="iHeight" value=""' . $GLOBALS["TBE_TEMPLATE"]->formWidth(4) . '>&nbsp;&nbsp;' . $LANG->getLL("border") . ': <input type="checkbox" name="iBorder" value="1"></td></tr>\';
				sz+=\'<tr><td\'+bgColor+\'>' . $LANG->getLL("margin_lr") . ': <input type="text" name="iHspace" value=""' . $GLOBALS["TBE_TEMPLATE"]->formWidth(4) . '>&nbsp;&nbsp;' . $LANG->getLL("margin_tb") . ': <input type="text" name="iVspace" value=""' . $GLOBALS["TBE_TEMPLATE"]->formWidth(4) . '></td></tr>\';
		//		sz+=\'<tr><td\'+bgColor+\'>Textwrapping: \'+alignSelector+\'&nbsp;&nbsp;Style: \'+styleSelector+\'</td></tr>\';
				sz+=\'<tr><td\'+bgColor+\'>' . $LANG->getLL("title") . ': <input type="text" name="iTitle"' . $GLOBALS["TBE_TEMPLATE"]->formWidth(20) . '></td></tr>\';
				sz+=\'<tr><td><input type="submit" value="' . $LANG->getLL("update") . '" onclick="return setImageProperties();"></td></tr>\';
				sz+=\'</form></table>\';
				return sz;
			}
			function setImageProperties()	{	//
				if (selectedImageRef)	{
					selectedImageRef.width=document.imageData.iWidth.value;
					selectedImageRef.height=document.imageData.iHeight.value;
					selectedImageRef.vspace=document.imageData.iVspace.value;
					selectedImageRef.hspace=document.imageData.iHspace.value;
					selectedImageRef.title=document.imageData.iTitle.value;
					selectedImageRef.alt=document.imageData.iTitle.value;

					selectedImageRef.border= (document.imageData.iBorder.checked ? 1 : 0);

					self.parent.parent.edHidePopup();
				}
				return false;
			}
			function insertImagePropertiesInForm()	{	//
				if (selectedImageRef)	{
					document.imageData.iWidth.value = selectedImageRef.width;
					document.imageData.iHeight.value = selectedImageRef.height;
					document.imageData.iVspace.value = selectedImageRef.vspace;
					document.imageData.iHspace.value = selectedImageRef.hspace;
					document.imageData.iTitle.value = selectedImageRef.title;
					if (parseInt(selectedImageRef.border))	{
						document.imageData.iBorder.checked = 1;
					}

				}
				return false;
			}

			var selectedImageRef = getCurrentImageRef();	// Setting this to a reference to the image object.
			-->
		</script>
		';

		// Starting content:
		$this->content = "";
		$this->content.=$this->doc->startPage($LANG->getLL('title', 1));
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function main() {
		global $LANG, $TYPO3_CONF_VARS, $FILEMOUNTS;

		$menu = '
			<!-- Tab menu -->
			<div class="tabs">
				<ul>';
		$bgcolor = 'class=""';
		$bgcolorA = 'class="current"';

		if ($this->act == "image" || GeneralUtility::_GP("cWidth")) { // If $this->act is specifically set to "image" or if cWidth is passed around...
			$menu.='<li ' . ($this->act == "image" ? $bgcolorA : $bgcolor) . ' title="' . str_replace('"', "'", $LANG->getLL("currentImage_msg")) . '"><span><a href="#" onclick="jumpToUrl(\'?act=image\');return false;">' . $LANG->getLL("currentImage") . '</a></span></li>' . "\n";
		}
		if (in_array("magic", $this->allowedItems)) {
			$menu.='<li ' . ($this->act == "magic" ? $bgcolorA : $bgcolor) . ' title="' . str_replace('"', "'", $LANG->getLL("magicImage_msg")) . '"><span><a href="#" onclick="jumpToUrl(\'?act=magic\');return false;">' . $LANG->getLL("magicImage") . '</a></span></li>' . "\n";
		}
		if (in_array("plain", $this->allowedItems)) {
			$menu.='<li ' . ($this->act == "plain" ? $bgcolorA : $bgcolor) . ' title="' . str_replace('"', "'", $LANG->getLL("plainImage_msg")) . '"><span><a href="#" onclick="jumpToUrl(\'?act=plain\');return false;">' . $LANG->getLL("plainImage") . '</a></span></li>' . "\n";
		}
		if (in_array("upload", $this->allowedItems)) {
			$menu.='<li ' . ($this->act == "upload" ? $bgcolorA : $bgcolor) . ' title="' . str_replace('"', "'", $LANG->getLL("uploadImage_msg")) . '"><span><a href="#" onclick="jumpToUrl(\'?act=upload\');return false;">' . $LANG->getLL("uploadImage") . '</a></span></li>' . "\n";
		}
		$menu.='
				</ul>
			</div>';

		$this->content .= $menu;
		$this->content .= '<div class="panel_wrapper">';

		if ($this->act != "upload") {

			// Getting flag for showing/not showing thumbnails:
			$noThumbs = $GLOBALS["BE_USER"]->getTSConfigVal("options.noThumbsInRTEimageSelect");

			if (!$noThumbs) {
				// MENU-ITEMS, fetching the setting for thumbnails from File>List module:
				$_MOD_MENU = array('displayThumbs' => '');
				$_MCONF['name'] = 'file_list';
				$_MOD_SETTINGS = BackendUtility::getModuleData($_MOD_MENU, GeneralUtility::_GP('SET'), $_MCONF['name']);
				$addParams = '&act=' . $this->act . '&expandFolder=' . rawurlencode($this->modData["expandFolder"]);
				$thumbNailCheck = '<fieldset><legend>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_file_list.php:displayThumbs', 1) . '</legend>' . BackendUtility::getFuncCheck('', 'SET[displayThumbs]', $_MOD_SETTINGS['displayThumbs'], 'rte_select_image.php', $addParams) . '</fieldset>';
			} else {
				$thumbNailCheck = '';
			}

			// prepare retrieving file folders
			/* @var $foldertree localFolderTree */
			$foldertree = GeneralUtility::makeInstance("localFolderTree");
			$foldertree->thisScript = $this->thisScript;
			$tree = $foldertree->getBrowsableTree();

			// try to determine correct folder
			$expandFolder = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('expandFolder');
			// TODO: what exactly is the use case here?
//            $curUrlArray = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('curUrl');
//			if ($curUrlArray['all']) {
//				$curUrlArray = \TYPO3\CMS\Core\Utility\GeneralUtility::get_tag_attributes($curUrlArray['all']);
//			}
//			$curUrlInfo = $this->parseCurUrl($curUrlArray['href'], $this->siteURL);
//            if (!$this->curUrlInfo['value'] || $this->curUrlInfo['act'] != $this->act) {
//                $cmpPath = '';
//            } else {
//                $cmpPath = $this->curUrlInfo['value'];
//                if (!isset($expandFolder)) {
//                    $expandFolder = $cmpPath;
//                }
//            }
			// get folder object for selected folder
			$selectedFolder = FALSE;
			if ($expandFolder) {
				$fileOrFolderObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->retrieveFileOrFolderObject($expandFolder);
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
			$files = '';
			if ($selectedFolder) {
				$files = $this->expandFolder($selectedFolder, $this->act == "plain", $noThumbs ? $noThumbs : !$_MOD_SETTINGS['displayThumbs']);
			}

			list(,, $specUid) = explode("_", GeneralUtility::_GP("PM"));

			$files = '<fieldset><legend>' . $GLOBALS['LANG']->getLL('images') . '</legend><div style="overflow: hidden;"><table><tr><td>' . $files . '</td></tr></table></div></fieldset>';
			$this->content.= '<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
			<tr>
				<td valign="top" style="width: 48%;"><fieldset><legend>' . $LANG->getLL("folderTree") . '</legend><div style="overflow: hidden;"><table><tr><td>' . $tree . '</td></tr></table></div></fieldset></td>
				<td valign="top" style="width: 1%;"><img src="clear.gif" width="5" alt="clear" /></td>
				<td valign="top" style="width: 48%;">' . $files . '</td>
			</tr>
			</table>' . $thumbNailCheck;
		} else if ($this->act == "upload") {

			// ***************************
			// Upload
			// ***************************
			// File-folders:
			$foldertree = GeneralUtility::makeInstance("localFolderTree");
			$foldertree->thisScript = $this->thisScript;
			$tree = $foldertree->getBrowsableTree();
			$this->content.= '<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
			<tr>
				<td valign="top" style="width: 100%;"><fieldset><legend>' . $LANG->getLL("folderTree") . '</legend><div style="overflow: hidden;"><table><tr><td>' . $tree . '</td></tr></table></div></fieldset></td>
			</tr>
			</table>';
			$fileProcessor = GeneralUtility::makeInstance("t3lib_basicFileFunctions");
			$fileProcessor->init($FILEMOUNTS, $TYPO3_CONF_VARS["BE"]["fileExtensions"]);
			$path = GeneralUtility::_GP("expandFolder");
			if (!$path || $path == "/" || !@is_dir($path)) {
				$path = $fileProcessor->findTempFolder(); // The closest TEMP-path is found
				if ($path) {
					$path.="/";
				}
			}
			if ($path && @is_dir($path)) {
				$this->content.=$this->uploadForm($path);
			}
		} else {

			$this->content.='</div>
			<script language="javascript" type="text/javascript">
		document.write(printCurrentImageOptions());
		insertImagePropertiesInForm();
			</script>
			';
		}

		$this->content.='</div>';
	}

	/**
	 * Print content of module
	 *
	 * @return	void
	 */
	function printContent() {
		$this->content.= $this->doc->endPage();
		echo $this->content;
	}

	/*	 * *************************
	 *
	 * OTHER FUNCTIONS:
	 *
	 * ************************* */

	/**
	 * @param	[type]		$expandFolder: ...
	 * @param	[type]		$plainFlag: ...
	 * @return	[type]		...
	 */
	function expandFolder(\TYPO3\CMS\Core\Resource\Folder $folder, $plainFlag = 0, $noThumbs = 0) {
		GeneralUtility::devLog("expand folder was called", "tinymce_rte", 0, array('expandFolder' => $folder));

		$folder = $folder ? $folder : GeneralUtility::_GP("expandFolder");
		$out = "";

		$resolutionLimit_x = $this->thisConfig['typo3filemanager.']['maxPlainImages.']['width'];
		$resolutionLimit_y = $this->thisConfig['typo3filemanager.']['maxPlainImages.']['height'];
		$fileTypes = $plainFlag ? "jpg,jpeg,gif,png" : $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"];

		// shortcut if empty or inaccessible
		if (!$folder || !$folder->checkActionPermission('read')) {
			GeneralUtility::devLog("no read permissions, quitting", 'tinymce_rte', 0);
			return $out;
		}

		// Create header element; The folder from which files are listed.
		$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
		$folderIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile('folder');
		$folderIcon .= htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($folder->getIdentifier(), $titleLen));
		$out .= '<a href="#" onclick="return link_insert(\'' . $folder->getCombinedIdentifier() . '\');">' . $folderIcon . '</a><br />';

		// filter and get files
		$filter = new \TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter();
		$filter->setAllowedFileExtensions($fileTypes);
		$folder->getStorage()->setFileAndFolderNameFilters(array(array($filter, 'filterFileList')));
		$items = $folder->getFiles();

		// prepare way to read image information
		$imgObj = GeneralUtility::makeInstance("t3lib_stdGraphic");
		$imgObj->init();
		$imgObj->mayScaleUp = 0;
		$imgObj->tempPath = PATH_site . $imgObj->tempPath;

		$count = 0;
		$totalItems = count($items);
		GeneralUtility::devLog("after filtering", 'tinymce_rte', 0, array('itemCount' => $totalItems, 'items' => $items));
		foreach ($items as $fileOrFolderObject) {
			/* @var $fileOrFolderObject File */
			$count++;

			/* @var $imgObj \TYPO3\CMS\Core\Imaging\GraphicalFunctions */

			// file specific data
			$fileIdentifier = $fileOrFolderObject->getUid();
			$fileExtension = $fileOrFolderObject->getExtension();
			$imgInfo = $imgObj->getImageDimensions($fileOrFolderObject->getForLocalProcessing());

			// prepare extension specific file symbol
			$size = ' (' . \TYPO3\CMS\Core\Utility\GeneralUtility::formatSize($fileOrFolderObject->getSize()) . 'bytes)';
			$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile($fileExtension, array('title' => $fileOrFolderObject->getName() . $size));

			// prepare essential data for linking
			$itemUid = 'file:' . $fileIdentifier;
			$iurl = GeneralUtility::rawUrlEncodeFP($fileOrFolderObject->getPublicUrl());



			// If the listed file turns out to be the CURRENT file, then show blinking arrow:
			$arrCol = '';
			if (($this->curUrlInfo['act'] == 'file' || $this->curUrlInfo['act'] == 'folder') && $currentIdentifier == $fileIdentifier) {
				$arrCol = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/blinkarrow_left.gif', 'width="5" height="9"') . ' class="c-blinkArrowL" alt="" />';
			}

			if (!$plainFlag) {
				$ATag = '<a href="#" onclick="return jumpToUrl(\'?insertMagicImage=' . rawurlencode($itemUid) . '\');">';
			} else {
				$ATag = '<a href="#" onclick="return insertImage(\'' . $iurl . '\', ' . $imgInfo[0] . ', ' . $imgInfo[1] . ');">';
			}
			$ATag_e = "</a>";
			if ($plainFlag && ($imgInfo[0] > $resolutionLimit_x || $imgInfo[1] > $resolutionLimit_y)) {
				$ATag = '';
				$ATag_e = '';
				$ATag2 = '';
				$ATag2_e = '';
			} else {
				$ATag2 = '<a href="#" onclick="launchView(\'' . rawurlencode($itemUid) . '\'); return false;">';
				$ATag2_e = '</a>';
			}

			$filenameAndIcon = $ATag . $icon . htmlspecialchars(GeneralUtility::fixed_lgd_cs($fileOrFolderObject->getName(), $titleLen)) . $ATag_e;


			// Put it all together for the file element:
			$out .= '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], ('gfx/ol/join' . ($count == $totalItems ? 'bottom' : '') . '.gif'), 'width="18" height="16"') . ' alt="" />'
					. $arrCol
//                    . '<a href="#" onclick="return link_insert(\'' . $itemUid . '\');">' 
//                        . $icon 
//                        . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($fileOrFolderObject->getName(), $titleLen)) 
//                    . '</a><br />';
					. $filenameAndIcon
					. '<br />';
//            $test = $ATag2
//                    .BackendUtility::getThumbNail(
//                            $this->doc->backPath.'thumbs.php', 
//                            $fileOrFolderObject->getForLocalProcessing(),
//                            'hspace="5" vspace="5" border="1"', 
//                            $this->thisConfig['typo3filemanager.']['thumbs.']['width'] . 'x' . $this->thisConfig['typo3filemanager.']['thumbs.']['height']
//                    ).$ATag2_e;
		}

		//$files = GeneralUtility::getFilesInDir($folder, $fileTypes, 1, 1);	// $extensionList="",$prependPath=0,$order="")
//        if (is_array($files))	{
//            reset($files);
//
//            $titleLen = intval($GLOBALS["BE_USER"]->uc["titleLen"]);
//            $picon = '<img src="'.$this->doc->backPath.'gfx/i/_icon_webfolders.gif" width="18" height="16" alt="folder" />';
//            $picon .= htmlspecialchars(GeneralUtility::fixed_lgd_cs(basename($folder),$titleLen));
//            $out .= '<span class="nobr">'.$picon.'</span><br />';
//
//            $imgObj = GeneralUtility::makeInstance("t3lib_stdGraphic");
//            $imgObj->init();
//            $imgObj->mayScaleUp=0;
//            $imgObj->tempPath=PATH_site.$imgObj->tempPath;
//
//            $lines=array();
//            while(list(,$filepath)=each($files))	{
//                $fI=pathinfo($filepath);
//
//                //$iurl = $this->siteUrl.GeneralUtility::rawUrlEncodeFP(substr($filepath,strlen(PATH_site)));
//                $iurl = GeneralUtility::rawUrlEncodeFP(substr($filepath,strlen(PATH_site)));
//                $imgInfo = $imgObj->getImageDimensions($filepath);
//
//                $icon = BackendUtility::getFileIcon(strtolower($fI["extension"]));
//                $pDim = $imgInfo[0]."x".$imgInfo[1]." pixels";
//                $size=" (".GeneralUtility::formatSize(filesize($filepath))."bytes, ".$pDim.")";
//                $icon = '<img src="'.$this->doc->backPath.'gfx/fileicons/'.$icon.'" style="width: 18px; height: 16px; border: none;" title="'.$fI["basename"].$size.'" class="absmiddle" alt="'.$icon.'" />';
//                if (!$plainFlag)	{
//                    $ATag = '<a href="#" onclick="return jumpToUrl(\'?insertMagicImage='.rawurlencode($filepath).'\');">';
//                } else {
//                    $ATag = '<a href="#" onclick="return insertImage(\''.$iurl.'\','.$imgInfo[0].','.$imgInfo[1].');">';
//                }
//                $ATag_e="</a>";
//                if ($plainFlag && ($imgInfo[0]>$resolutionLimit_x || $imgInfo[1]>$resolutionLimit_y))	{
//                    $ATag="";
//                    $ATag_e="";
//                    $ATag2="";
//                    $ATag2_e="";
//                } else {
//                    $ATag2='<a href="#" onclick="launchView(\''.rawurlencode($filepath).'\'); return false;">';
//                    $ATag2_e="</a>";
//                }
//
//                $filenameAndIcon=$ATag.$icon.htmlspecialchars(GeneralUtility::fixed_lgd_cs(basename($filepath),$titleLen)).$ATag_e;
//
//                $lines[]='<tr class="bgColor4"><td nowrap="nowrap">'.$filenameAndIcon.'&nbsp;</td></tr><tr><td nowrap="nowrap" class="pixel">'.$pDim.'&nbsp;</td></tr>';
//                $lines[]='<tr><td>'.(
//                    $noThumbs ?
//                    "" :
//                    $ATag2.BackendUtility::getThumbNail($this->doc->backPath.'thumbs.php',$filepath,'hspace="5" vspace="5" border="1"', $this->thisConfig['typo3filemanager.']['thumbs.']['width'] . 'x' . $this->thisConfig['typo3filemanager.']['thumbs.']['height']).$ATag2_e).
//                    '</td></tr>';
//                $lines[]='<tr><td><img src="clear.gif" style="width: 1px; height: 3px;" alt="clear" /></td></tr>';
//            }
//            $out.='<table border="0" cellpadding="0" cellspacing="1">'.implode("",$lines).'</table>';
//        }
		return $out;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$path: ...
	 * @return	[type]		...
	 */
	function uploadForm($path) {
		global $LANG, $SOBE;

		//	debug($path);
		$count = 1;
		$header = GeneralUtility::isFirstPartOfStr($path, PATH_site) ? substr($path, strlen(PATH_site)) : $path;
		$code.='<fieldset><legend>' . $LANG->getLL("uploadImage") . '</legend><FORM action="' . $this->doc->backPath . 'tce_file.php" method="post" name="editform" enctype="' . $GLOBALS["TYPO3_CONF_VARS"]["SYS"]["form_enctype"] . '"><table border=0 cellpadding=0 cellspacing=3><tr><td>';
		$code.="<strong>" . $LANG->getLL("path") . ":</strong> " . $header . "</td></tr><tr><td>";
		for ($a = 1; $a <= $count; $a++) {
			$code.='<input type="File" name="upload_' . $a . '"' . $this->doc->formWidth(35) . ' size="50">
				<input type="Hidden" name="file[upload][' . $a . '][target]" value="' . $path . '">
				<input type="Hidden" name="file[upload][' . $a . '][data]" value="' . $a . '"><BR>';
		}
		$code.='
			<input type="Hidden" name="redirect" value="' . t3lib_extMgm::extRelPath('tinymce_rte') . 'mod2/rte_select_image.php?act=magic&expandFolder=' . rawurlencode($path) . '&RTEtsConfigParams=' . rawurlencode(GeneralUtility::_GP("RTEtsConfigParams")) . '">
			<img src="clear.gif" height="8" width="100%" alt="clear" />
			<input type="Submit" name="submit" value="' . $LANG->sL("LLL:EXT:lang/locallang_core.php:file_upload.php.submit") . '"><img src="clear.gif" height="8" width="100%" alt="clear" />
			<div id="c-override">
				<input type="checkbox" name="overwriteExistingFiles" value="1" /> ' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_misc.php:overwriteExistingFiles', 1) . '
			</div>

		</td>
		</tr>
		</table></FORM></fieldset>';

		return $code;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$str: ...
	 * @return	[type]		...
	 */
	function barheader($str) {
		global $LANG, $SOBE;

		return '<table border=0 cellpadding=2 cellspacing=0 width=100% class="bgColor5"><tr><td><strong>' . $str . '</strong></td></tr></table>';
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$str: ...
	 * @return	[type]		...
	 */
	function printCurrentUrl($str) {
		global $LANG, $SOBE;

		return '<table border=0 cellpadding=0 cellspacing=0 width=100% class="bgColor5"><tr><td><strong>Current Link:</strong> ' . $str . '</td></tr></table>';
	}

	/**
	 * For RTE/link: Parses the incoming URL and determines if it's a page, file, external or mail address.
	 *
	 * @param string $href HREF value tp analyse
	 * @param string $siteUrl The URL of the current website (frontend)
	 * @return array Array with URL information stored in assoc. keys: value, act (page, file, spec, mail), pageid, cElement, info
	 * @todo Define visibility
	 */
	public function parseCurUrl($href, $siteUrl) {
		$href = trim($href);
		if ($href) {
			$info = array();
			// Default is "url":
			$info['value'] = $href;
			$info['act'] = 'url';
			$specialParts = explode('#_SPECIAL', $href);
			// Special kind (Something RTE specific: User configurable links through: "userLinks." from ->thisConfig)
			if (count($specialParts) == 2) {
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
			} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($href, $siteUrl)) {
				// If URL is on the current frontend website:
				// URL is a file, which exists:
				if (file_exists(PATH_site . rawurldecode($href))) {
					$info['value'] = rawurldecode($href);
					if (@is_dir((PATH_site . $info['value']))) {
						$info['act'] = 'folder';
					} else {
						$info['act'] = 'file';
					}
				} else {
					// URL is a page (id parameter)
					$uP = parse_url($href);

					$pp = preg_split('/^id=/', $uP['query']);
					$pp[1] = preg_replace('/&id=[^&]*/', '', $pp[1]);
					$parameters = explode('&', $pp[1]);
					$id = array_shift($parameters);
					if ($id) {
						// Checking if the id-parameter is an alias.
						if (!\TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($id)) {
							list($idPartR) = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordsByField('pages', 'alias', $id);
							$id = intval($idPartR['uid']);
						}
						$pageRow = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL('pages', $id);
						$titleLen = intval($GLOBALS['BE_USER']->uc['titleLen']);
						$info['value'] = ((((($GLOBALS['LANG']->getLL('page', 1) . ' \'') . htmlspecialchars(\TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($pageRow['title'], $titleLen))) . '\' (ID:') . $id) . ($uP['fragment'] ? ', #' . $uP['fragment'] : '')) . ')';
						$info['pageid'] = $id;
						$info['cElement'] = $uP['fragment'];
						$info['act'] = 'page';
						$info['query'] = $parameters[0] ? '&' . implode('&', $parameters) : '';
					}
				}
			} else {
				// Email link:
				if (strtolower(substr($href, 0, 7)) == 'mailto:') {
					$info['value'] = trim(substr($href, 7));
					$info['act'] = 'mail';
				}
			}
			$info['info'] = $info['value'];
		} else {
			// NO value inputted:
			$info = array();
			$info['info'] = $GLOBALS['LANG']->getLL('none');
			$info['value'] = '';
			$info['act'] = 'page';
		}
		return $info;
	}

}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce_rte/mod2/rte_select_image.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce_rte/mod2/rte_select_image.php']);
}


// Make instance:
$SOBE = GeneralUtility::makeInstance('SC_rte_select_image');
$SOBE->preinit();
$SOBE->magicProcess();
$SOBE->init();
$SOBE->main();
$SOBE->printContent();
?>