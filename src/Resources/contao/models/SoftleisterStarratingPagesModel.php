<?php
/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @author     Softleister - Hagen Klemp
 * @package    contao-starrating-bundle
 * @license    LGPL-3.0-or-later
 * @filesource
 */

namespace Softleister\ContaoStarRatingBundle\Model;


use Contao\Model;

class SoftleisterStarratingPagesModel extends \Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_starrating_pages';

    public static function findByUrlAndToken( $url, $token='', array $arrOptions = [] )
    {
        if( empty( $pid ) || ($token === '') ) {
            return null;
        }

        $t = static::$strTable;

        $arrColumns = [];
        $arrColumns[] = "$t.url ='" . $url . "'";
        $arrColumns[] = "$t.token='" . $token . "'";

        return static::findOneBy( $arrColumns, null, $arrOptions );
    }
}