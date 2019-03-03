<?php

/**
 * @copyright 2018 Sven Rhinow
 * @author    Sven Rhinow
 * @package   contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

/**
 * back-end modules
 */

$GLOBALS['BE_MOD']['starrating'] = array
(
    'starrating_pages' => array
    (
        'tables' => array('tl_starrating_pages','tl_starrating_entries'),
    ),
    'starrating_settings' => array
    (
        'tables' => array('tl_starrating_settings'),
    ),
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_starrating_pages'] = \Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingPagesModel::class;
$GLOBALS['TL_MODELS']['tl_starrating_entries'] = \Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingEntriesModel::class;
$GLOBALS['TL_MODELS']['tl_starrating_settings'] = \Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingSettingsModel::class;

/**
 * HOOKS
 *
 * Hooks are stored in a global array called "TL_HOOKS". You can register your
 * own functions by adding them to the array.
 *
 * $GLOBALS['TL_HOOKS'] = array
 * (
 *    'hook_1' => array
 *    (
 *       array('MyClass', 'myPostLogin'),
 *       array('MyClass', 'myPostLogout')
 *    )
 * );
 *
 * Hooks allow you to add functionality to the core without having to modify the
 * source code by registering callback functions to be executed on a particular
 * event. For more information see https://contao.org/manual.html.
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('srhinow_starrating.listener.insert_tags','onReplaceInsertTags');