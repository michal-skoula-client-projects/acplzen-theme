<?php

/**
 * Nicer var dump with `<pre>` tag formatting
 *
 * @param  mixed  $var
 * @param  array  $vars
 *
 * @return void
 *
 * @author Michal Škoula
 */
function dump(mixed $var, ...$vars): void
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';

	if($vars) {
		foreach($vars as $variables) {
			echo '<br>';
			echo '<pre>';
			var_dump($variables);
			echo '</pre>';
		}
	}
}

/**
 * Dumps a value and returns
 *
 * @param  mixed  $value The value to be returned
 *
 * @return mixed returns `$value`
 */
function rdump(mixed $value): mixed
{
	dump($value);
	return $value;
}