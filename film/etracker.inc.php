<?php
/**
 * This material may not be reproduced, displayed, modified or distributed
 * without the express prior written permission of the copyright holder.
 *
 * Copyright (c) etracker GmbH. All Rights Reserved
 */

abstract class etracker
{
	const CODE_VERSION = '5.0';
	const STATIC_HOST = 'static.etracker.com';

	/**
	 * @var array
	 */
	private static $defaultParameters = [
		'et_pagename'		=> '',		// pagename
		'et_areas'			=> '',		// slash delimited area names
		'et_tval'			=> '',		// advanced services: target value
		'et_tsale'			=> '',		// advanced services: lead (0), sale (1) or storno(2)
		'et_tonr'			=> '',		// advanced services: target order number
		'et_basket'			=> '',		// advanced services: shopping cart
	];

	/**
	 * @param string $secureCode crypted statistic id (secure code)
	 * @param array $parameters counting parameters
	 * @param array $options option parameters
	 * @return string
	 */
	public static function getCode($secureCode, $parameters = NULL, $options = [])
	{
		$showAll			= self::getOption($options, 'show_all', false);
		$slaveCodes			= self::getOption($options, 'slave_codes', []);
		$respectDNT			= self::getOption($options, 'respect_dnt', true);
		$productIdentifier 	= self::getOption($options, 'product_identifier');
		$cookieLifetime		= self::getOption($options, 'cookie_lifetime');
		$cookieUpgradeURL	= self::getOption($options, 'cookie_upgrade_url');
		$blockCookies		= self::getOption($options, 'block_cookies', true);
		$banner				= self::getOption($options, 'banner', 'etracker tracklet ' . self::CODE_VERSION);
		$commentAllParams	= self::getOption($options, 'comment_all_parameters', false);

		if(!preg_match("/^[0-9a-zA-Z]+$/", $secureCode))
			return '';

		$parameters =
		self::checkParameter($parameters ? array_merge(self::$defaultParameters, $parameters) : self::$defaultParameters);

		$code  = "<!-- Copyright (c) 2000-".date("Y")." etracker GmbH. All rights reserved. -->\n";
		$code .= "<!-- This material may not be reproduced, displayed, modified or distributed -->\n";
		$code .= "<!-- without the express prior written permission of the copyright holder. -->\n";
		$code .= "<!-- $banner -->\n";
		$code .= self::getParameters($showAll, $parameters, $commentAllParams);
		$blockCookiesString = self::getBlockCookiesString($blockCookies);
		$cookieLifetimeString = self::getCookieLifetimeString($cookieLifetime);
		$cookieUpgradeURLString = self::getCookieUpgradeURLString($cookieUpgradeURL);
		$respectDNTString = self::getRespectDNTString($respectDNT);
		$productIdentifierString = self::getProductIdentifierString($productIdentifier);
		$slaveCodesString = self::getSlaveCodesString($slaveCodes);
		$code .=
		'<script id="_etLoader" type="text/javascript" charset="UTF-8"' .
		$blockCookiesString .
		$cookieLifetimeString .
		$cookieUpgradeURLString .
		$respectDNTString .
		$productIdentifierString .
		' data-secure-code="' . $secureCode . '"' .
		$slaveCodesString .
		' src="//' . self::STATIC_HOST . '/code/e.js"></script>' . "\n";

		$code .= "<!-- $banner end -->\n\n";

		return $code;
	}

	/**
	 * @param array $options option parameters
	 * @param string $optName name of the option (in snake_case)
	 * @param mixed|null $defaultValue
	 * @return mixed|null the option value
	 */
	private static function getOption(array $options, $optName, $defaultValue = NULL)
	{
		$value = @$options[$optName];
		return is_null($value) ? $defaultValue : $value;
	}

	/**
	 * @param boolean $blockCookies
	 * @return string
	 */
	private static function getBlockCookiesString($blockCookies)
	{
		return $blockCookies ? ' data-block-cookies="true"' : '';
	}

	/**
	 * @param integer|null $cookieLifetime
	 * @return string
	 */
	private static function getCookieLifetimeString($cookieLifetime)
	{
		return $cookieLifetime ? ' data-cookie-lifetime="' . $cookieLifetime . '"' : '';
	}

	/**
	 * @param string|null $cookieUpgradeURL
	 * @return string
	 */
	private static function getCookieUpgradeURLString($cookieUpgradeURL)
	{
		return $cookieUpgradeURL ? ' data-cookie-upgrade-url="' . htmlspecialchars($cookieUpgradeURL) . '"' : '';
	}

	/**
	 * @param boolean $respectDNT
	 * @return string
	 */
	private static function getRespectDNTString($respectDNT)
	{
		return $respectDNT ? ' data-respect-dnt="true"' : '';
	}

	/**
	 * @param boolean $productIdentifier
	 * @return string
	 */
	private static function getProductIdentifierString($productIdentifier)
	{
		return $productIdentifier ? ' data-product-identifier="'.$productIdentifier.'"' : '';
	}

	/**
	 * @param array $slaveCodes
	 * @return string
	 */
	private static function getSlaveCodesString(array $slaveCodes)
	{
		return $slaveCodes
			? ' data-slave-codes="' . implode(',', $slaveCodes) . '"'
			: '';
	}

	/**
	 * @param bool $showAll
	 * @param array $parameters
	 * @param bool $commentAll
	 * @return string
	 */
	private static function getParameters($showAll = false, array $parameters = [], $commentAll = false)
	{
		$code = '';

		foreach($parameters as $name => $value)
		{
			if($value || $showAll)
			{
				$code .= ($commentAll ? '// ' : '') . 'var '.$name.' = '.json_encode($value).";\n";
			}
		}

		$ret = '';
		if($code)
		{
			$ret .= "<script type=\"text/javascript\">\n";
			$ret .= $code;
			$ret .= "</script>\n";
		}
		return $ret;
	}

	/**
	 * @param array
	 * @return array
	 */
	public static function checkParameter(array $parameter = [])
	{
		$result = [];

		foreach($parameter as $name => $value)
		{
			switch($name)
			{
				case 'et_cust':
					$result[$name] = $value ? 1 : 0;
					break;

				case 'et_pagename':
				case 'et_areas':
				case 'et_basket':
					$result[$name] = rawurlencode($value);
					break;

				case 'et_ilevel':
				case 'et_tsale':
				case 'et_lpage':
					$result[$name] = (int) $value;
					break;

				case 'et_tval':
					$result[$name] = (float) str_replace(',', '.', $parameter[$name]);
					break;

				case 'et_sub':
				case 'et_tonr':
					$result[$name] = str_replace('"', '', $parameter[$name]);
					break;

				case 'et_cdi':
					$result[$name] = $parameter[$name];
					break;

				case 'cc_attributes':
					$result[$name] = $value;
					break;
			}
		}
		return $result;
	}
}
