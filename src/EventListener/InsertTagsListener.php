<?php

/**
 * @author    Sven Rhinow
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Srhinow\ContaoStarRatingBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\FrontendTemplate;
use Contao\System;
use Srhinow\ContaoStarRatingBundle\Helper\Helper;
use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingEntriesModel;
use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingPagesModel;
use Srhinow\ContaoStarRatingBundle\Model\SrhinowStarratingSettingsModel;

//use Srhinow\ThemeSectionArticleModel;
//use Srhinow\ModuleThemeArticle;

/**
 * Handles insert tags for news.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class InsertTagsListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * @var array
     */
    private $supportedTags = [
        'starrating',
    ];

    /**
     * Constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Replaces news insert tags.
     *
     * @param string $tag
     *
     * @return string|false
     */
    public function onReplaceInsertTags($tag)
    {
        $elements = explode('::', $tag);
        $key = strtolower($elements[0]);

        if (\in_array($key, $this->supportedTags, true)) {

            return $this->getStarratingStars($key, $elements[1]);
        }

        return false;
    }


    /**
     * Replaces a THEME-ARTICLE-related insert tag.
     *
     * @param string $insertTag
     * @param string $idOrAlias
     *
     * @return string
     */
    private function getStarratingStars($insertTag, $idOrAlias)
    {
        global $objPage;
        $this->framework->initialize();

        /** @var SrhinowStarratingSettingsModel $adapter */
        $adapter = $this->framework->getAdapter(SrhinowStarratingSettingsModel::class);

        if (null === ($objRow = $adapter->findByIdOrAlias($idOrAlias))) {
            return '';
        }

        $Template = new FrontendTemplate($objRow->fe_template);
        $Template->settingId = $objRow->id;

        //defaults
        $Template->isVoted = false;
        $Template->stats = [];

        /** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = System::getContainer()->get('session');
        $session = $objSession->all();

        // falls schon ein Eintrag zu dieser Seite existiert die Statistik holen
        $objSrPage = SrhinowStarratingPagesModel::findOneBy('pageId', $objPage->id);
        if(null !== $objSrPage) {
            $Template->stats = Helper::getStatisticsFromPage($objSrPage->id);

            //prüfen ob der aktuelle besuche die Seite schon bewertet hat
            if(strlen($session['STARRATING_TOKEN']) > 0) {
                $objIsVoted = SrhinowStarratingEntriesModel::findByPidAndToken($objSrPage->id,$session['STARRATING_TOKEN']);
                
                if(null !== $objIsVoted) $Template->isVoted = true;
            }
        }

        return $Template->parse();
    }

}
