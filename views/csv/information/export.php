<?php
	if ($vars['data'] && is_array($vars['data'])) {
		foreach ($vars['data'] as $data) {
			if (is_array($data)) {
				echo implode(',', $data) . "\n";
			}
		}
	}