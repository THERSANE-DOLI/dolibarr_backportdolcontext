# CHANGELOG BACKPORTDOLCONTEXT FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)


- FIX: A tool or a context var defined with overwrite could never be replaced :   1.3.2
  - The second definition threw « Cannot redefine property » (ex: setEventMessage). An entry defined with overwrite
    may now be replaced by another call with overwrite; one defined without overwrite stays protected.

- FIX: Compatibility with Dolibarr 18 and 19 :   1.3.1
  - The module required Dolibarr 20 and disabled itself below. Minimum version is now Dolibarr 18,
    with a backport of the CommonHookActions class (Dolibarr 19).

- NEW: Backport reloadDocumentLine hook listener for document lines drag and drop :   1.3.0
  - Re-enable drag and drop on a document line reloaded in ajax (V24 core/tpl/ajaxrow.tpl.php behaviour),
    only the handles of the reloaded row are bound

- Backport initNewContent system V1.1

- Initial version V1.0
