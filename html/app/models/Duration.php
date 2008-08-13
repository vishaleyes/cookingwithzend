<?php

class Duration
{
	/**
	* All in one method
	*
	* @param   int|array  $duration  Array of time segments or a number of seconds
	* @return  string
	*/

	function toString ($duration, $periods = null)
	{
		if (!is_array($duration)) {
			$duration = Duration::int2array($duration, $periods);
		}

		return Duration::array2string($duration);
	}


	/**
	* Return an array of date segments.
	*
	* @param        int $seconds Number of seconds to be parsed
	* @return       mixed An array containing named segments
	*/
	function int2array ($seconds, $periods = null)
	{        
		// Define time periods
		if (!is_array($periods)) {
			$periods = array (
				'years'     => 31556926,
				'months'    => 2629743,
				'weeks'     => 604800,
				'days'      => 86400,
				'hours'     => 3600,
				'minutes'   => 60,
				'seconds'   => 1
			);
		}

		// Loop
		$seconds = (float) $seconds;
		foreach ($periods as $period => $value) {
			$count = floor($seconds / $value);

			if ($count == 0) {
				continue;
			}
	
			$values[$period] = $count;
			$seconds = $seconds % $value;
		}

		// Return
		if (empty($values)) {
			$values = null;
		}

		return $values;
	}

	/**
	* Return a string of time periods.
	*
	* @package      Duration
	* @param        mixed $duration An array of named segments
	* @return       string
	*/
	function array2string ($duration)
	{
		if (!is_array($duration)) {
			return false;
		}

		foreach ($duration as $key => $value) {
			$segment_name = substr($key, 0, -1);
			$segment = $value . ' ' . $segment_name; 

			// Plural
			if ($value != 1) {
				$segment .= 's';
			}

			$array[] = $segment;
		}

		$str = implode(', ', $array);
		return $str;
	}

}

?>
