<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

namespace Softleister\ContaoStarRatingBundle\Model;


use Contao\Model;

class SoftleisterStarratingSettingsModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_starrating_settings';
}