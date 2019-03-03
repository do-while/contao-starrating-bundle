<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

namespace Srhinow\ContaoStarRatingBundle\EventListener\Dca;

use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;
use Symfony\Component\Config\Definition\Exception\Exception;

class SrhinowStarratingSettings extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * Auto-generate the reference alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->reference_title);
        }
        
        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_starrating WHERE alias=? AND id!=?")
            ->execute($varValue, $dc->id);

        // Check whether the news alias exists
        if ($objAlias->numRows)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * Return all info templates as array
     *
     * @param DataContainer $dc
     * @return array
     */
    public function getTemplates(DataContainer $dc)
    {
        $arrTemplates = \Controller::getTemplateGroup('starrating_');

        return $arrTemplates;
    }
}