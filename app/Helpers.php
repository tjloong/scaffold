<?php

use Carbon\Carbon;

/**
 * Get app version
 */
function get_app_version()
{
	$files = collect(scandir(base_path('.git/refs/tags')))->filter(function($file) {
		return !in_array($file, ['.', '..']);
	});

	return $files->last() ?? '1.0.0';
}

/**
 * Get url mime type
 */
function get_url_mime_type($url)
{
	$contentType = collect(get_headers($url))->first(function($val, $key) {
		return Str::startsWith($val, 'Content-Type');
	});

	$mime = Str::replaceFirst('Content-Type: ', '', $contentType);

	return $mime;
}

/**
 * Set referal code into cookies for 7 days
 * 
 * @param boolean $isNew
 * @return void
 */
function set_ref_to_cookies($isNew = true)
{
	if ($isNew) {
		$ref = request()->query('ref');
		$cookie = request()->cookie('_ref');
	
		if ($ref && $ref !== $cookie) {
			Cookie::queue('_ref', $ref, (7 * 24 * 60));
		}
	}
	else {
		Cookie::expire('_ref');
	}
}

/**
 * Get referal code from cookies
 */
function get_ref_from_cookies()
{
	if ($ref = request()->query('ref')) return $ref;

	return request()->cookie('_ref');
}

/**
 * Set locale from route prefix
 */
function set_locale()
{
	$path = request()->path();
	$params = explode('/', $path);
	$locales = ['zh-my', 'en'];

	if (in_array($params[0], $locales)) {
		app()->setLocale($params[0]);
	}
}

/**
 * Get current locale url
 */
function locale_url($url)
{
	$locale = app()->currentLocale();

	if ($locale === 'en') return $url;
	else return '/' . $locale . str_replace($locale, '', $url);
}

/**
 * Format number to currency
 *
 * @return string
 */
function currency($num, $symbol = null, $bracket = true)
{
	$num = (float)$num ?: 0;
	$currency = number_format($num, 2);

	if ($symbol) $currency = "$symbol $currency";
	if ($bracket && $num < 0) $currency = '(' . str_replace('-', '', $currency) . ')';

	return $currency;
}

/**
 * Parse date range from string
 *
 * @param string $str
 * @return object
 */
function date_range($str, $strict = false)
{
	$from = null;
	$to = null;
	$today = Carbon::today();

	// custom from and to
	if (is_array($str) && (isset($str['from']) || isset($str['to']))) {
		$from = Carbon::parse($str['from']);
		$to = Carbon::parse($str['to']);
	}
	// string form
	else if (is_string($str)) {
		// today
		if (strtolower($str) == 'today') {
			$from = $today;
			$to = $today;
		}
		// this fiscal year
		elseif (strtolower($str) === 'this fiscal') {
			$from = tenant()->fiscal_start;
			$to = tenant()->fiscal_end;
		}
		// last fiscal year
		elseif (strtolower($str) === 'last fiscal') {
			$from = tenant()->fiscal_start->subYear();
			$to = tenant()->fiscal_end->subYear();
		}
		// this year
		elseif (strtolower($str) == 'this year') {
			$from = Carbon::parse('first day of January');
			$to = Carbon::parse('last day of December');
		}
		// last year
		elseif (strtolower($str) == 'last year') {
			$from = Carbon::parse('first day of January ' . (date('Y') - 1));
			$to = Carbon::parse('last day of December ' . (date('Y') - 1));
		}
		// day range
		elseif (strpos(strtolower($str), 'days')) {
			$n = (integer)trim(str_replace('days', '', strtolower($str)));
			$from = $today->copy()->subDays($n - 1);
			$to = $today->copy();
		}
		// month range
		elseif (strpos(strtolower($str), 'months')) {
			$n = (integer)trim(str_replace('months', '', strtolower($str)));
			$sub = $today->copy()->subMonths($n - 1);

			if ($strict) {
				$from = $sub;
				$to = $today->copy();
			}
			else {
				$from = Carbon::parse(implode('-', [$sub->year, $sub->month, '01']));
				$to = Carbon::parse(implode('-', [$today->year, $today->month, $today->daysInMonth]));
			}
		}
		// year range
		elseif (strpos(strtolower($str), 'years')) {
			$n = (integer)trim(str_replace('years', '', strtolower($str)));
			$sub = $today->copy()->subYears($n - 1);

			if ($strict) {
				$from = $sub;
				$to = $today->copy();
			}
			else {
				$from = Carbon::parse(implode('-', [$sub->year, '01', '01']));
				$to = Carbon::parse(implode('-', [$today->year, '12', '31']));
			}
		}
		// custom range
		elseif (strpos(strtolower($str), ' to ')) {
			$split = explode(' to ', strtolower($str));
			$from = Carbon::parse(trim($split[0]));
			$to = Carbon::parse(trim($split[1]));
		}
		else {
			$from = Carbon::parse(strtolower($str));
			$to = Carbon::parse(strtolower($str));
		}
	}
	else return $str;

	return (object)[
		'from' => $from->copy()->toDateString(),
		'to' => $to->copy()->toDateString(),
		'diffInDays' => $from->copy()->diffInDays($to),
		'diffInMonths' => $from->copy()->diffInMonths($to->copy()->endOfMonth()),
		'diffInYears' => $from->copy()->diffInYears($to->copy()->endOfYear()),
		'string' => $str,
	];
}
