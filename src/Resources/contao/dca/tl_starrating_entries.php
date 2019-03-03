<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_starrating_entries
 */
$GLOBALS['TL_DCA']['tl_starrating_entries'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_starrating_pages',
        'switchToEdit'                => true,
        'enableVersioning'            => false,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 8,
            'fields'                  => array('tstamp'),
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('vote','tstamp','token'),
            'format'                  => 'Vote: %s  (%s) - Token: %s',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_entries']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_entries']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array(),
        'default'                     => '{title_legend},url,settingId,vote,token;{status_legend},published'
    ),

    // Fields
    'fields' => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_entries']['pid'],
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 11,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'flag'                    => 8,
            'eval'                    => array('mandatory'=>true, 'rgxp' => 'datetim'),
        ],
        'sorting' => [
            'sql'					  => "int(10) unsigned NOT NULL default '0'"
        ],
        'url' => [

            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_entries']['url'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255,'tl_class'=>'full'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'settingId' => [

            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_entries']['url'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_starrating_settings.title',
            'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr w50'],
            'sql'					  => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ],
        'vote' => [

            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_entries']['vote'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255,'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'token' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_entries']['token'],
            'exclude'                 => true,
            'search'                  => true,
            'default'                 => '',
            'inputType'               => 'text',
            'eval'                    => array('readonly' => true,'tl_class'=>'w50'),
            'sql'                     => "varchar(35) NOT NULL default ''",
        ],
    ]
);

