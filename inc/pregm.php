<?php

function pregm($what, $where, $return=1, $keys='Ui') {
	$search="/$what/$keys";
	if (preg_match($search,$where,$matches)) {
		unset($matches[0]);
		if ($return==1) {
			return $matches[1];
		} else {
			return $matches;
		}
	} else {
		return false;
	}
}

function pregma($what, $where, $return=1, $keys='Ui') {
	$search="/$what/$keys";
	if (preg_match_all($search,$where,$matches)) {
		unset($matches[0]);
		if ($return==1) {
			return $matches[1];
		} else {
			return $matches;
		}
	} else {
		return false;
	}
}

?>