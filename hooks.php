<?php
/**
 * @package SMF Legacy Karma
 * @author digger <digger@mysmf.net>
 * @link https://github.com/realdigger/SMF-Legacy-Karma
 * @copyright (c) 2015-2019, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.0
 */

global $context, $user_info;

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify that you put this file in the same place as SMF\'s index.php and SSI.php files.');

if ((SMF == 'SSI') && !$user_info['is_admin'])
    die('Admin privileges required.');

if (!empty($context['uninstalling']))
    $call = 'remove_integration_function';
else
    $call = 'add_integration_function';

$hooks = array(
    'integrate_pre_include' => '$sourcedir/Mod-LegacyKarma.php',
    'integrate_pre_load' => 'loadLegacyKarmaHooks'
);

foreach ($hooks as $hook => $function)
    $call($hook, $function);

if (SMF == 'SSI')
    echo 'Database changes are complete! <a href="/">Return to the main page</a>.';
