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

class SoftleisterStarratingEntriesModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_starrating_entries';

    /**
     * Find published agreement by their parent (project) ID
     *
     * @param integer $pid    		An Project-ID from actual FrontendUser
     * @param string  $status		optional filter
     * @param integer $intLimit    	An optional limit
     * @param integer $intOffset   	An optional offset
     * @param array   $arrOptions  	An optional options array
     *
     * @return \Model\Collection|\NewsModel[]|\NewsModel|null A collection of models or null if there are no news
     */
    public static function findByPidAndToken($pid, $token='', array $arrOptions=array())
    {
        if (empty($pid) || $token === '')
        {
            return null;
        }

        $t = static::$strTable;

        $arrColumns[] = "$t.pid ='" . $pid."'";
        $arrColumns[] = "$t.token='".$token."'";

        return static::findOneBy($arrColumns, null, $arrOptions);
    }

    /**
     * Count votes by Starrating-Pages
     *
     * @param $memberId
     * @param string $status
     * @param array $arrOptions
     * @return int|null
     */
    public static function countVotesByPage($pageId, $published='', array $arrOptions=array())
    {
        if (empty($pageId))
        {
            return null;
        }

        $t = static::$strTable;

        // Rechnnung nach  aktuellem Mitglied filtern
        $arrColumns = array("$t.pid=".$pageId);

        //falls angegeben nach Status der Rechnung filtern
        if ($published != '')
        {
            $arrColumns[] = "$t.published=".$published;
        }

        return static::countBy($arrColumns, null, $arrOptions);
    }
}