<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

namespace Srhinow\ContaoStarRatingBundle\Model;


use Contao\Model;

class SrhinowStarratingPagesModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_starrating_pages';

    public static function findByUrlAndToken($url, $token='', array $arrOptions=array())
    {
        if (empty($pid) || $token === '')
        {
            return null;
        }

        $t = static::$strTable;

        $arrColumns[] = "$t.url ='" . $url."'";
        $arrColumns[] = "$t.token='".$token."'";

        return static::findOneBy($arrColumns, null, $arrOptions);
    }
}