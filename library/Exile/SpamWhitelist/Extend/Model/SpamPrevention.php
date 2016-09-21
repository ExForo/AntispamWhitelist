<?php

class Exile_SpamWhitelist_Extend_Model_SpamPrevention extends XFCP_Exile_SpamWhitelist_Extend_Model_SpamPrevention
{
	protected function _checkSpamPhrases($content, array $extraParams, Zend_Controller_Request_Http $request)
	{
		$result = parent::_checkSpamPhrases($content, $extraParams, $request);
		$options = XenForo_Application::getOptions();

		if ($options->spamWhitePhrases)
		{
			$phrases = preg_split('/\r?\n/', trim($options->spamWhitePhrases), -1, PREG_SPLIT_NO_EMPTY);
			foreach ($phrases AS $phrase)
			{
				$phrase = trim($phrase);
				if (!strlen($phrase))
				{
					continue;
				}

				$origPhrase = $phrase;

				if ($phrase[0] != '/')
				{
					$phrase = preg_quote($phrase, '#');
					$phrase = str_replace('\\*', '[\w"\'/ \t]*', $phrase);
					$phrase = '#(?<=\W|^)(' . $phrase . ')(?=\W|$)#iu';
				}
				else
				{
					if (preg_match('/\W[\s\w]*e[\s\w]*$/', $phrase))
					{
						// can't run a /e regex
						continue;
					}
				}

				try
				{
					if (preg_match($phrase, $content))
					{
						$result = self::RESULT_ALLOWED;

						break;
					}
				}
				catch (ErrorException $e) {}
			}
		}

		return $result;
	}
}