<?php
/**
 * @package SMF Legacy Karma
 * @author digger http://mysmf.ru
 * @copyright 2015
 * @license CC BY-NC-ND 4.0 http://creativecommons.org/licenses/by-nc-nd/4.0/
 * @version 1.0
 */

global $context, $user_info;

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify that you put this file in the same place as SMF\'s index.php and SSI.php files.');

if ((SMF == 'SSI') && !$user_info['is_admin'])
    die('Admin privileges required.');

// List settings here in the format: setting_key => default_value.  Escape any "s. (" => \")
$mod_settings = array(
    'legacy_karma_enabled' => 0,
    'legacy_karma_label' => 'Karma',
);

// Update mod settings if applicable
foreach ($mod_settings as $new_setting => $new_value) {
    if (!isset($modSettings[$new_setting]))
        updateSettings(array($new_setting => $new_value));
}

if (SMF == 'SSI')
    echo 'Database changes are complete! <a href="/">Return to the main page</a>.';