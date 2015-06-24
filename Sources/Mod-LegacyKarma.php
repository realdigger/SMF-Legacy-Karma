<?php
/**
 * @package SMF Legacy Karma
 * @author digger http://mysmf.ru
 * @copyright 2015
 * @license CC BY-NC-ND 4.0 http://creativecommons.org/licenses/by-nc-nd/4.0/
 * @version 1.0
 */

if (!defined('SMF'))
    die('Hacking attempt...');


/**
 * Load all needed hooks
 */
function loadLegacyKarmaHooks()
{
    global $modSettings;

    add_integration_function('integrate_general_mod_settings', 'changeLegacyKarmaSettings', false);
    add_integration_function('integrate_menu_buttons', 'addLegacyKarmaCopyright', false);

    if (empty($modSettings['legacy_karma_enabled'])) return;

    add_integration_function('integrate_menu_buttons', 'printLegacyKarmaForMember', false);
    //add_integration_function('integrate_query_message', 'showLegacyKarmaInMessage', false);
}


/**
 * Generate admin section for my settings
 * @param $config_vars array config vars
 */
function changeLegacyKarmaSettings(&$config_vars = array())
{
    loadLanguage('LegacyKarma/');

    $config_vars = array_merge($config_vars,
        array(
            array('check', 'legacy_karma_enabled'),
            array('text', 'legacy_karma_label'),
            // TODO groups permissions to view karma
        )
    );
}


/**
 * Show legacy karma in message
 */
function printLegacyKarmaForMember()
{
    global $user_profile, $txt, $modSettings;

    loadLanguage('LegacyKarma/');

    if (!empty($user_profile))
        foreach (array_keys($user_profile) as $user_id) {
            $legacy_karma = getLegacyKarmaByMemberID($user_id);

            if (!empty($legacy_karma)) {
                $user_profile[$user_id]['personal_text'] .= $modSettings['legacy_karma_label'] . ': ' . $legacy_karma;
                unset($legacy_karma);
            }
        }
}


/**
 * Get legacy karma value for member id
 * @param int $member_id
 * @return array|bool
 */
function getLegacyKarmaByMemberID($member_id = 0)
{
    global $smcFunc;

    if (!$member_id)
        return false;

    $request = $smcFunc['db_query']('', '
			SELECT karma_good, karma_bad
			FROM {db_prefix}members
			WHERE id_member = {int:member_id}
			LIMIT 1', array(
            'member_id' => $member_id,
        )
    );

    list ($karma_good, $karma_bad) = $smcFunc['db_fetch_row']($request);
    $smcFunc['db_free_result']($request);

    if (!empty($karma_good) || !empty($karma_bad))
        return (!empty($karma_good) ? '+' . $karma_good : '') . (!empty($karma_good) && !empty($karma_bad) ? '/' : '') . (!empty($karma_bad) ? '-' . $karma_bad : '');
    else
        return false;
}


/**
 * Add mod copyright to forum credits page
 */
function addLegacyKarmaCopyright()
{
    global $context;

    if ($context['current_action'] == 'credits')
        $context['copyrights']['mods'][] = '<a href="http://mysmf.ru/mods/legacy-karma">Legacy Karma</a> @ 2015, digger</a>';
}