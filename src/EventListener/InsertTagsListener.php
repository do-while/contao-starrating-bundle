<?php

/**
 * @author    Sven Rhinow
 * @author    Softleister - Hagen Klemp
 * @package   contao-starrrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Softleister\ContaoStarRatingBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\System;
use Softleister\ContaoStarRatingBundle\Helper\Helper;
use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingEntriesModel;
use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingPagesModel;
use Softleister\ContaoStarRatingBundle\Model\SoftleisterStarratingSettingsModel;

//use Softleister\ThemeSectionArticleModel;
//use Softleister\ModuleThemeArticle;

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
        'starview',
    ];

    /**
     * Constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct( ContaoFrameworkInterface $framework )
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
    public function onReplaceInsertTags( $tag )
    {
        $elements = explode( '::', $tag );
        $key = strtolower( $elements[0] );
        $elements[] = '';                           // Element[2] ergänzen, wenn nicht vorhanden

        if( \in_array( $key, $this->supportedTags, true ) ) {

            return $this->getStarratingStars( $key, $elements[1], $elements[2] );
        }
        return false;
    }


    /**
     * Replaces a THEME-ARTICLE-related insert tag.
     *
     * @param string $insertTag
     * @param string $idOrAlias
     * @param string $urlAlias
     *
     * @return string
     */
    private function getStarratingStars( $insertTag, $idOrAlias, $urlAlias = '' )
    {
        $this->framework->initialize( );

        /** @var SoftleisterStarratingSettingsModel $adapter */
        $adapter = $this->framework->getAdapter( SoftleisterStarratingSettingsModel::class );

        if( null === ( $objRow = $adapter->findByIdOrAlias( $idOrAlias ) ) ) {
            return '';
        }

        $Template = new FrontendTemplate( $objRow->fe_template );
        $Template->settingId = $objRow->id;
        $Template->mode      = $objRow->mode;

        //defaults
        $Template->tag       = $insertTag;
        $Template->isVoted   = false;
        $Template->stats     = [];

        /** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = System::getContainer( )->get( 'session' );
        $session = $objSession->all( );

        // falls schon ein Eintrag zu dieser Seite existiert die Statistik holen
        $url = empty($urlAlias) ? \Environment::get( 'url' ) . \Environment::get( 'requestUri' ) : $urlAlias;
        $Template->url = $url;
        
        $objSrPage = SoftleisterStarratingPagesModel::findOneBy( 'url', $url );
        if( null !== $objSrPage ) {
            
            $Template->stats = Helper::getStatisticsFromPage( $objSrPage->id );

            if( $insertTag !== 'starrating' ) {
                $Template->isVoted = true;                  // nur anzeigen
            }
            else {
                //prüfen ob der aktuelle besuche die Seite schon bewertet hat
                $session = $_SESSION['STARRATING_TOKEN'];
                if( strlen( $session ) > 0 ) {
                    $objIsVoted = SoftleisterStarratingEntriesModel::findByPidAndToken( $objSrPage->id, $session );

                    if( null !== $objIsVoted ) {
                        $Template->isVoted = true;          // wurde bereits bewertet
                    }
                }
            }
        }

        return $Template->parse( );
    }

}
