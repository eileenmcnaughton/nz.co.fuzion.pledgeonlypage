<?php

require_once 'pledgeonlypage.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function pledgeonlypage_civicrm_config(&$config) {
  _pledgeonlypage_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function pledgeonlypage_civicrm_xmlMenu(&$files) {
  _pledgeonlypage_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function pledgeonlypage_civicrm_install() {
  _pledgeonlypage_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function pledgeonlypage_civicrm_uninstall() {
  _pledgeonlypage_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function pledgeonlypage_civicrm_enable() {
  _pledgeonlypage_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function pledgeonlypage_civicrm_disable() {
  _pledgeonlypage_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function pledgeonlypage_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _pledgeonlypage_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function pledgeonlypage_civicrm_managed(&$entities) {
  _pledgeonlypage_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function pledgeonlypage_civicrm_caseTypes(&$caseTypes) {
  _pledgeonlypage_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function pledgeonlypage_civicrm_angularModules(&$angularModules) {
_pledgeonlypage_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function pledgeonlypage_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _pledgeonlypage_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function pledgeonlypage_civicrm_buildForm($formName, &$form) {

  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    if ($form->get('id') == 2) {
      $defaults['is_pledge'] = 1;
      $defaults['pledge_installments'] = 1;
      $defaults['pledge_frequency_interval'] = 1;
      $form->setDefaults($defaults);
      $form->addDate('pledge_start_date', ts('First payment date'), FALSE, array('formatType' => 'activityDate'));

      CRM_Core_Region::instance('contribution-main-pledge-block')->update('default', array(
        'disabled' => TRUE,
      ));
      CRM_Core_Region::instance('contribution-main-pledge-block')->add(array(
        'template' => 'pledgeOnlyBlock.tpl',
      ));
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function pledgeonlypage_civicrm_postProcess($formName, &$form) {
  if (($formName == 'CRM_Contribute_Form_Contribution_Main' || $formName == 'CRM_Contribute_Form_Contribution_Main')
    && !empty($form->_submitValues['pledge_start_date'])) {
    // Stash the start date for later.
    pledgeonlypage_stash_pledge_start_date(date('Ymd', strtotime($form->_submitValues['pledge_start_date'])));
  };
}

/**
 * Static for pledge start date.
 *
 * @param string $date
 *
 * @return string
 */
function pledgeonlypage_stash_pledge_start_date($date = NULL) {
  static $start_date;
  if (!empty($date)) {
    $start_date = $date;
  }
  return $start_date;
}

/**
 * @param $op
 * @param $objectName
 * @param $id
 * @param $params
 */
function pledgeonlypage_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Pledge' && empty($id) && ($start_date =  pledgeonlypage_stash_pledge_start_date()) != FALSE) {
    $params['start_date'] = $start_date;
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param array $files
 * @param CRM_Core_Form $form
 * @param array $errors
 */
function pledgeonlypage_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  $form->setElementError('pledge_installments', NULL);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function pledgeonlypage_civicrm_preProcess($formName, &$form) {

}

*/
