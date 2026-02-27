<?php
/* Copyright (C) 2026 John BOTELLA <john.botella@thersane.fr>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    backportdolcontext/class/actions_backportdolcontext.class.php
 * \ingroup backportdolcontext
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

require_once DOL_DOCUMENT_ROOT.'/core/class/commonhookactions.class.php';

/**
 * Class ActionsBackportDolContext
 */
class ActionsBackportDolContext extends CommonHookActions
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 * @var array Errors
	 */
	public $errors = array();


	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var ?string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var int		Priority of hook (50 is used if value is not defined)
	 */
	public $priority;


	/**
	 * Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}


	/**
	 * Execute action
	 *
	 * @param	array			$parameters		Array of parameters
	 * @param	CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param	string			$action      	'add', 'update', 'view'
	 * @return	int         					Return integer <0 if KO,
	 *                           				=0 if OK but we want to process standard actions too,
	 *                            				>0 if OK and we want to replace standard actions.
	 */
	public function getNomUrl($parameters, &$object, &$action)
	{
		global $db, $langs, $conf, $user;
		$this->resprints = '';
		return 0;
	}

	public function isInPublicFolder(): bool
	{
		$scriptPath = realpath($_SERVER['SCRIPT_FILENAME']);
		$publicPath = realpath(DOL_DOCUMENT_ROOT . '/public');

		if ($scriptPath === false || $publicPath === false) {
			return false;
		}

		return strncmp($scriptPath, $publicPath, strlen($publicPath)) === 0;
	}

	/**
	 * Overloading the addHtmlHeader function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             Return integer < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function addHtmlHeader($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter

		$contexts = explode(':', $parameters['context']);

		if($this->isInPublicFolder() && !getDolGlobalInt('BACKPORT_CONTEXT_PUBLIC')) {
			return 0;
		}

		if (INTVAL(DOL_VERSION) < 24) {	    // do something only for the context 'somecontext1' or 'somecontext2'


			/**
			 * ====================================
			 * DEFINE DOLIBARR JS CONTEXT AND TOOLS
			 * ====================================
			 * see Documentation at admin/tools/ui/dolibarr-context/index.php
			 */
			$jsContextVars = [
				'DOL_VERSION' => DOL_VERSION,
				'DOL_URL_ROOT' => DOL_URL_ROOT,
			];

			$jsContextPathUrl = dol_buildpath('backportdolcontext/backport/includes/dolibarr-js-context', 1);
			$jsContextFiles = [
				'dolibarr-context.umd.js', // The js Dolibarr context definition
				'dolibarr-tool.seteventmessage.js' // The first tools to help dev for easy event in js
			];

			if (! defined('NOREQUIRETRAN')) {
				// Langs tool see Documentation at admin/tools/ui/dolibarr-context/index.php
				$jsContextFiles[] = 'dolibarr-tool.langs.js';
				$jsContextVars['MAIN_LANG_DEFAULT'] = $langs->getDefaultLang();// For langs tool
				$jsContextVars['DOL_LANG_INTERFACE_URL'] = dol_buildpath('backportdolcontext/backport/langs/langs-tool-interface.php', 1);// For langs tool
			}

			// Load context and all js tools
			foreach ($jsContextFiles as $jsContextFile) {
				print '<script nonce="'.getNonce().'" src="'.$jsContextPathUrl.'/'.$jsContextFile.'" ></script>'."\n";
			}

			// DEFINE FIRST NEEDED JS CONTEXT VARS
			print '<script nonce="'.getNonce().'">Dolibarr.setContextVars('.json_encode($jsContextVars).');</script>'."\n";

			// -- END OF DEFINITION OF DOLIBARR JS CONTEXT AND TOOLS
		}

	}

}
