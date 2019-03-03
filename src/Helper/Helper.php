<?php

/**
 * @author    Sven Rhinow
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Srhinow\ContaoStarRatingBundle\Helper;


use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingEntriesModel;

class Helper
{
    public static function uniqidReal($lenght = 35)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public static function getStatisticsFromPage($pageId)
    {
        $arrReturn = ['min' => 0, 'max'=>0, 'sum' => 0, 'average'=>0,'count'=>0];
        if((int) $pageId < 1) return $arrReturn;

        $count = SrhinowStarratingEntriesModel::countVotesByPage($pageId);
        if($count > 0) $arrReturn['count'] = $count;

        $objEntries = SrhinowStarratingEntriesModel::findBy('pid',$pageId);
        if(null === $arrReturn) return $arrReturn;

        while($objEntries->next()) {
            if($objEntries->vote > $arrReturn['max']) $arrReturn['max'] = $objEntries->vote;
            $arrReturn['sum'] += $objEntries->vote;
        }

        $arrReturn['average'] = $arrReturn['sum'] / $arrReturn['count'];
        return $arrReturn;
    }
}