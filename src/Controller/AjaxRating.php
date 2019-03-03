<?php

/**
 * @author    Sven Rhinow
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Srhinow\ContaoStarRatingBundle\Controller;


use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Input;
use Contao\Session;
use Contao\System;
use GuzzleHttp\Exception\RequestException;
use Srhinow\ContaoStarRatingBundle\Helper\Helper;
use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingEntriesModel;
use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingPagesModel;
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

        /** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = System::getContainer()->get('session');
        $session = $objSession->all();

        if(strlen($session['STARRATING_TOKEN']) < 1) $session['STARRATING_TOKEN'] = Helper::uniqidReal();

        $objSession->replace($session);

        $objSrPage = SrhinowStarratingPagesModel::findOneBy('pageId', $params['p']);

        //wenn starating-page nicht exisitert -> neu anlegen
        if(null === $objSrPage) {
            $options = [
                'tstamp' => time(),
                'pageId' => $params['p']
            ];

            $objNewPage = new SrhinowStarratingPagesModel();
            $objNewPage->setRow($options);
            $objNewPage->save();
            $SrPageId = $objNewPage->id;
        } else {
            $SrPageId = $objSrPage->id;
        }

        //Eintrag einfügen falls für den Besucher (Token) noch nicht vorhanden
        $objEntry = SrhinowStarratingEntriesModel::findByPidAndToken($SrPageId, $session['STARRATING_TOKEN']);
        if(null === $objEntry) {
            $entryOptions =
                [
                    'tstamp' => time(),
                    'pid' => $SrPageId,
                    'vote' => $params['v'],
                    'setingId' => $params['s'],
                    'token' => $session['STARRATING_TOKEN'],
                    'url' => $params['u']
                ];

            $newEntry = new SrhinowStarratingEntriesModel();
            $newEntry->setRow($entryOptions)->save();
        }

        // aktuelle Werte holen/berechnen
        $return = Helper::getStatisticsFromPage($SrPageId);

        return new JsonResponse($return);
    }
}
