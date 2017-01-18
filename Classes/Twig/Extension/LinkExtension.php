<?php

namespace DMK\T3twig\Twig\Extension;

/***************************************************************
 * Copyright notice
 *
 * (c) 2017 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DMK\T3twig\Twig\EnvironmentTwig;

/**
 * Class LinkExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class LinkExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3link',
                [$this, 'renderLink'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3url',
                [$this, 'renderUrl'],
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * @param EnvironmentTwig $env
     * @param                   $label
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return array
     */
    public function renderLink(EnvironmentTwig $env, $label, $dest, $params = [], $tsPath = 'link.')
    {
        $rnBaseLink = $this->makeRnbaseLink($env, $label, $dest, $params, $tsPath);

        return $rnBaseLink->makeTag();
    }

    /**
     * @param EnvironmentTwig $env
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return string
     */
    public function renderUrl(EnvironmentTwig $env, $dest, $params = [], $tsPath = 'link.')
    {
        $rnBaseLink = $this->makeRnbaseLink($env, $label = '', $dest, $params, $tsPath);

        return $rnBaseLink->makeUrl(false);
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_linkExtension';
    }

    /**
     * @param EnvironmentTwig $env
     * @param                   $label
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return \tx_rnbase_util_Link
     */
    private function makeRnbaseLink(EnvironmentTwig $env, $label, $dest, $params, $tsPath = 'link.')
    {
        $configurations = $env->getConfigurations();
        $confId         = $env->getConfId();

        $rnBaseLink = $configurations->createLink();
        $rnBaseLink->label($label);
        $rnBaseLink->initByTS($configurations, $confId.$tsPath, $params);
        // set destination only if set, so 0 for current page can be used
        if (!empty($dest)) {
            $rnBaseLink->destination($dest);
        }

        if (($extTarget = $configurations->get($confId.$tsPath.'extTarget'))) {
            $rnBaseLink->externalTargetAttribute($extTarget);
        }

        return $rnBaseLink;
    }
}