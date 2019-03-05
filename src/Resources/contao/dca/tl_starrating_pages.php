<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_starrating_pages
 */
$GLOBALS['TL_DCA']['tl_starrating_pages'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => ['tl_starrating_entries'],
        'enableVersioning'            => false,
        'switchToEdit'                => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('pageId'),
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('url'),
            'format'                  => '%s',
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
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_pages']['edit'],
                'href'                => 'table=tl_starrating_entries',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ),
            'editheader' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_pages']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.gif',
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_pages']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_pages']['delete'],
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
        'default'                     => '{title_legend},pageId'
    ),

    // Fields
    'fields' => [

        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
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
        'pageId' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_pages']['pageId'],
            'foreignKey'              => 'tl_page.title',
            'inputType'               => 'text',
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 11,
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'belongsTo', 'load'=>'eager'),
        ]
    ]
);

