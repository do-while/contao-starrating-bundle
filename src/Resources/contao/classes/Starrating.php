<?php

/**
 * @copyright 2018 Felix Pfeiffer : Neue Medien
 * @author    Felix Pfeiffer : Neue Medien
 * @author    Sven Rhinow
 * @package   contao-news-simple-bundle
 * @license   LGPL
 *
 */

namespace Srhinow\ContaoNewsSimpleBundle;

use Contao\Frontend;
use Contao\FrontendTemplate;
use Contao\StringUtil;

class NewsSimple extends Frontend
{
    public function addSimpleNewsText($objNewsTemplate, $arrArticle, $objModule)
    {
        global $objPage;

        if(strlen($arrArticle['newsText'])>0)
        {
            // Clean the RTE output
            if ($objPage->outputFormat == 'xhtml')
            {
                $strText = StringUtil::toXhtml($arrArticle['newsText']);
            }
            else
            {
                $strText = StringUtil::toHtml5($arrArticle['newsText']);
            }

            // Add the static files URL to images
            if (TL_FILES_URL != '')
            {
                $path = $GLOBALS['TL_CONFIG']['uploadPath'] . '/';
                $strText = str_replace(' src="' . $path, ' src="' . TL_FILES_URL . $path, $strText);

            }

            $objTemplate = new FrontendTemplate('ce_text');

            $objTemplate->text = StringUtil::encodeEmail($strText);
            $objTemplate->class = 'ce_text';

            // Add an image
            if ($arrArticle['addImage'] && $arrArticle['singleSRC'] != '')
            {
                $objModel = \FilesModel::findByUuid($arrArticle['singleSRC']);

                if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path)) {

                    $arrImage = $arrArticle;
                    $arrImage['singleSRC'] = $objModel->path;
                    $this->addImageToTemplate($objTemplate, $arrImage);
                }
            }

            $strText = $objTemplate->parse();

            if($GLOBALS['TL_CONFIG']['newsSimpleNoElements'] == 1)
            {
                $objNewsTemplate->text = $strText;
            }
            else
            {
                $objNewsTemplate->text = $strText . $objNewsTemplate->text;
            }

        }
    }
}