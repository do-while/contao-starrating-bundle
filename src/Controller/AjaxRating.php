<?php

/**
 * @author    Sven Rhinow
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Softleister\ContaoStarRatingBundle\Controller;


use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Input;
use Contao\Session;
use Contao\System;
use GuzzleHttp\Exception\RequestException;
use Softleister\ContaoStarRatingBundle\Helper\Helper;
use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingEntriesModel;
use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingPagesModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxRating
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $params = json_decode($request->get('p'),true);

        if(!is_array($params) || count($params) < 1) return new JsonResponse(['error'=>true,'errormsg'=>'"params" ist kein Array']);
        if((int) $params['p'] < 1) return new JsonResponse(['error'=>true,'errormsg'=>'Paramter "p" (PageId) ist leer']);
        if((int) $params['v'] < 1) return new JsonResponse(['error'=>true,'errormsg'=>'Paramter "v" (Vote) ist leer']);
        if(strlen($params['u']) < 1) return new JsonResponse(['error'=>true,'errormsg'=>'Paramter "u" (URL) ist leer']);
        if((int) $params['s'] < 1) return new JsonResponse(['error'=>true,'errormsg'=>'Paramter "s" (SettingsId) ist leer']);

        session_start();
        $session_token = $_SESSION['STARRATING_TOKEN'];
        if(strlen($session_token) < 1) $session_token = Helper::uniqidReal();
        $_SESSION['STARRATING_TOKEN'] = $session_token;

        $objSrPage = SoftleisterStarratingPagesModel::findOneBy('url', $params['u']);

        //wenn starating-page nicht exisitert -> neu anlegen
        if(null === $objSrPage) {
            $options = [
                'tstamp' => time(),
                'pageId' => $params['p'],
                'url' => $params['u']
            ];

            $objNewPage = new SoftleisterStarratingPagesModel();
            $objNewPage->setRow($options);
            $objNewPage->save();
            $SrPageId = $objNewPage->id;
        } else {
            $SrPageId = $objSrPage->id;
        }

        //Eintrag einfügen falls für den Besucher (Token) noch nicht vorhanden
        $objEntry = SoftleisterStarratingEntriesModel::findByPidAndToken($SrPageId, $session_token);
        $newEntryId = 0;
        if(null === $objEntry) {
            $entryOptions =
                [
                    'tstamp' => time(),
                    'pid' => $SrPageId,
                    'vote' => $params['v'],
                    'setingId' => $params['s'],
                    'token' => $session_token,
                    'url' => $params['u']
                ];

            $newEntry = new SoftleisterStarratingEntriesModel();
            $newEntry->setRow($entryOptions)->save();
            $newEntryId = $newEntry->id;
        }

        // aktuelle Werte holen/berechnen
        $return = Helper::getStatisticsFromPage($SrPageId);
        $return['average'] = number_format($return['average'],2,',','.');
        $return['new'] = ($newEntryId > 0)? true : false;
        return new JsonResponse($return);
    }
}
