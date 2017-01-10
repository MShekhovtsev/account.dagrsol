<?php

namespace App\Libraries\FormValidator\Converter\JqueryValidation;

class Converter extends \App\Libraries\FormValidator\Converter\Base\Converter {

	public static $rule;
	public static $message;
	public static $route;

	public function __construct()
	{
		self::$rule = new Rule();
		self::$message = new Message();
		self::$route = new Route();
	}

}