<?php

/**
 * leads Extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2011-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-leads
 */

/**
 * Add the tl_lead_export table to form module
 */
$GLOBALS['BE_MOD']['content']['form']['tables'][] = 'tl_lead_export';

/**
 * Fake back end module
 */
array_insert($GLOBALS['BE_MOD'], 1, array('leads'=> array
(
    'lead' => array
    (
        'tables'        => array('tl_lead', 'tl_lead_data'),
        'javascript'    => 'system/modules/leads/assets/leads.min.js',
        'stylesheet'    => 'system/modules/leads/assets/leads.min.css',
        'show'          => array('tl_lead', 'show'),
        'export'        => array('tl_lead', 'export'),
        'notification'  => array('tl_lead', 'sendNotification'),
    ),
)));

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadLanguageFile'][]  = array('Leads', 'loadLeadName');
$GLOBALS['TL_HOOKS']['getUserNavigation'][] = array('Leads', 'loadBackendModules');
$GLOBALS['TL_HOOKS']['processFormData'][]   = array('Leads', 'processFormData');

/**
 * Leads export types
 */
$GLOBALS['LEADS_EXPORT'] = array
(
    'csv' => array('LeadsExport', 'exportCsv'),
);

/**
 * Add the XLS export if the PHPExcel extension is installed
 */
if (class_exists('PHPExcel')) {
    $GLOBALS['LEADS_EXPORT']['xls'] = array('LeadsExport', 'exportXls');
    $GLOBALS['LEADS_EXPORT']['xlsx'] = array('LeadsExport', 'exportXlsx');
}
