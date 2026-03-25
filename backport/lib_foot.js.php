<?php
/* Copyright (C) 2017       Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * or see https://www.gnu.org/
 */

/**
 * \file       htdocs/core/js/lib_foot.js.php
 * \brief      File that include javascript functions (included if option use_javascript activated)
 */

if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', 1);
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1);
}
if (!defined('NOREQUIREMENU')) {
	define('NOREQUIREMENU', 1);
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', 1);
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}

session_cache_limiter('public');

// Load Dolibarr environment
$res=0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (! $res && ! empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res=@include($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php");
// Try main.inc.php into web root detected using web root caluclated from SCRIPT_FILENAME
$tmp=empty($_SERVER['SCRIPT_FILENAME'])?'':$_SERVER['SCRIPT_FILENAME'];$tmp2=realpath(__FILE__); $i=strlen($tmp)-1; $j=strlen($tmp2)-1;
while($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i]==$tmp2[$j]) { $i--; $j--; }
if (! $res && $i > 0 && file_exists(substr($tmp, 0, ($i+1))."/main.inc.php")) $res=@include(substr($tmp, 0, ($i+1))."/main.inc.php");
if (! $res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php")) $res=@include(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php");
// Try main.inc.php using relative path
if (! $res && file_exists("../main.inc.php")) $res=@include("../main.inc.php");
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php");
if (! $res && file_exists("../../../../main.inc.php")) $res=@include("../../../../main.inc.php");
if (! $res) die("Include of main fails");
/**
 * @var Conf $conf
 * @var Translate $langs
 *
 * @var int $dolibarr_nocache
 */


/*
 * View
 */

// Define javascript type
top_httphead('text/javascript; charset=UTF-8');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}


$jsConst = [
	'DOL_URL_ROOT' => DOL_URL_ROOT,
	'classfortooltiponclicktextWidth' => ($conf->browser->layout == 'phone' ? max((empty($_SESSION['dol_screenwidth']) ? 0 : $_SESSION['dol_screenwidth']) - 20, 320) : 700),
	'dol_no_mouse_hover' => !empty($conf->dol_no_mouse_hover)
];


include __DIR__ . '/lib_initTooltips.js';

?>

// Wrapper to show tooltips (html or onclick popup)
/* JS CODE TO ENABLE Tooltips on all object with class classfortooltip */
jQuery(function() {

	const footerConst = <?php print json_encode($jsConst); ?>;

	if(!footerConst.dol_no_mouse_hover) {

		// --------------------------------
		// Dynamic reload via Dolibarr hook
		// --------------------------------
		if (typeof Dolibarr !== "undefined" && Dolibarr.on) {
			// Because Dolibarr context isn't in all Dolibarr page and lib_foot.js.php can be loaded everywhere
			// it useful to check Dolibarr context is loaded before but we are already in a jQuery(function() so event Dolibarr:Ready can't be used here
			Dolibarr.on('initNewContent', ({targets}) => {
				targets.forEach(container => {
					initTooltips(container);
					initAjaxTooltips(container, footerConst.DOL_URL_ROOT);
				})
			});
		};
	}


	// --------------------------------
	// Dynamic reload via Dolibarr hook
	// --------------------------------

	if (typeof Dolibarr !== "undefined" && Dolibarr.on) {
		// Because Dolibarr context isn't in all Dolibarr page and lib_foot.js.php can be loaded everywhere
		// it useful to check Dolibarr context is loaded before but we are already in a jQuery(function() so event Dolibarr:Ready can't be used here
		Dolibarr.on('initNewContent', ({ targets }) => {
			targets.forEach(container => initTooltipDialogs(container, footerConst.classfortooltiponclicktextWidth));
		});
	};

});

