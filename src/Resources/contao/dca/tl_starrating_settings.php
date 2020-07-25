<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 *
 * @author    Softleister - Hagen Klemp
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_starrating_settings
 */
$GLOBALS['TL_DCA']['tl_starrating_settings'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'            => false,
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
            'fields'                  => array('title'),
            'flag'					  => 1,
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('title','alias'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
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
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_settings']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'attributes'          => 'class="contextmenu"'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_settings']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_starrating_settings']['delete'],
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
        'default'                     => '{title_legend},title,alias;{template_legend},mode,fe_template;{publish_legend},published;{notice_legend:hide},notice'
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
        'title' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255,'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'alias' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['alias'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'],
			'save_callback'           => [
                                            ['tl_starrating_settings', 'generateAlias']
                                         ],
            'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
        ],
        'mode' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['mode'],
            'default'                 => 'basic',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => ['basic', 'checkmark', 'coinFlip', 'fade', 'grow', 'growRotate', 'heart', 'heartbeat', 'rotate', 'slot'],
			'reference'               => &$GLOBALS['TL_LANG']['tl_starrating_settings']['mode_'],
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'clr w50'),
            'sql'					  => "varchar(16) NOT NULL default ''"
        ],
        'fe_template' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['fe_template'],
            'default'                 => 'starrating_default',
            'exclude'                 => true,
            'inputType'               => 'select',
			'options_callback' => static function ()
			{
				return \Contao\Controller::getTemplateGroup( 'starrating_', [] );
			},
            'eval'                    => ['tl_class'=>'w50'],
            'sql'					  => "varchar(32) NOT NULL default ''"
        ],
        'published' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'flag'                    => 1,
            'inputType'               => 'checkbox',
            'eval'                    => ['doNotCopy'=>true],
            'sql'					  => "char(1) NOT NULL default ''"
        ],
        'notice' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_starrating_settings']['notice'],
            'exclude'                 => true,
            'search'		          => true,
            'filter'                  => false,
            'inputType'               => 'textarea',
            'eval'                    => ['mandatory'=>false, 'cols'=>'10', 'rows'=>'10', 'style'=>'height:100px', 'rte'=>false],
            'sql'					  => "text NULL"
        ]
    ]
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Softleister
 */
class tl_starrating_settings extends \Contao\Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('\Contao\BackendUser', 'User');
	}

	/**
	 * Auto-generate an article alias if it has not been set yet
	 *
	 * @param mixed                $varValue
	 * @param Contao\DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias( $varValue, \Contao\DataContainer $dc )
	{
		$aliasExists = function (string $alias) use ($dc): bool {
			return $this->Database->prepare( "SELECT id FROM tl_starrating_settings WHERE alias=? AND id!=?" )->execute( $alias, $dc->id )->numRows > 0;
		};

		// Generate an alias if there is none
		if( $varValue == '' ) {
			$varValue = \Contao\System::getContainer()->get('contao.slug')->generate( $dc->activeRecord->title, $dc->activeRecord->id, $aliasExists );
		}
		else if( $aliasExists( $varValue ) ) {
			throw new Exception( sprintf( $GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue ) );
		}

		return $varValue;
	}

}