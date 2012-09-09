<?php

spl_autoload_register(function ($name) {
		require_once('class'.strtolower(preg_replace('/[A-Z]/', '_$0', $name)).'.php');
	}
);

