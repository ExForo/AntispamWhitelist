<?php

class Exile_SpamWhitelist_Listener
{
	public static function load_class($class, array &$extend)
	{
		if ($class == 'XenForo_Model_SpamPrevention')
		{
			$extend[] = 'Exile_SpamWhitelist_Extend_Model_SpamPrevention';
		}
	}
}