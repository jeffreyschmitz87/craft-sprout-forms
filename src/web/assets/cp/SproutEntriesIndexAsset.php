<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutforms\web\assets\cp;

use barrelstrength\sproutbasefields\web\assets\selectother\SelectOtherFieldAsset;
use barrelstrength\sproutforms\web\assets\dragula\DragulaAsset;
use barrelstrength\sproutbase\web\assets\cp\CpAsset as SproutBaseCpAsset;
use barrelstrength\sproutforms\web\assets\fontawesome\FontAwesomeAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset as CraftCpAsset;

class SproutEntriesIndexAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@barrelstrength/sproutforms/web/assets/cp/dist';

        $this->depends = [
            CraftCpAsset::class
        ];

        $this->css = [
            'css/sproutforms-charts.css',
            'css/sproutforms-cp.css',
            'css/sproutforms-forms-ui.css'
        ];

        $this->js = [
            'js/sprout-entries-index.js'
        ];

        parent::init();
    }
}