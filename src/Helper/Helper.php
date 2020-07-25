<?php

/**
 * @author    Sven Rhinow
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Softleister\ContaoStarRatingBundle\Helper;


use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingEntriesModel;

class Helper
{
    public static function uniqidReal( $length = 35 )
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if( function_exists( "random_bytes" ) ) {
            $bytes = random_bytes( ceil( $length / 2 ) );
        } 
        else if( function_exists( "openssl_random_pseudo_bytes" ) ) {
            $bytes = openssl_random_pseudo_bytes( ceil( $length / 2 ) );
        } 
        else {
            throw new Exception( "no cryptographically secure random function available" );
        }
        return substr( bin2hex( $bytes ), 0, $length );
    }


    public static function getStatisticsFromPage($pageId)
    {
        $arrReturn = ['min' => 0, 'max'=>0, 'sum' => 0, 'average'=>0, 'count'=>0];
        if( (int) $pageId < 1 ) return $arrReturn;

        $count = SoftleisterStarratingEntriesModel::countVotesByPage( $pageId );
        if( $count > 0 ) $arrReturn['count'] = $count;

        $objEntries = SoftleisterStarratingEntriesModel::findBy( 'pid', $pageId );
        if( null === $objEntries ) return $arrReturn;

        while( $objEntries->next() ) {
            if( $objEntries->vote > $arrReturn['max'] ) $arrReturn['max'] = $objEntries->vote;
            $arrReturn['sum'] += $objEntries->vote;
        }

        $arrReturn['average'] = $arrReturn['sum'] / $arrReturn['count'];
        return $arrReturn;
    }
}