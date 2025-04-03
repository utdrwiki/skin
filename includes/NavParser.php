<?php

namespace MediaWiki\Skins\Vector;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Skin;
use Exception;
use Title;
use Sanitizer;

class NavParser {
	const NAVBAR_DECLARATION_TITLE = "Wiki-navigation";
	public Skin $skin;
	public Title $messageTitle;

	public function __construct( Skin $skin ) {
		$this->skin = $skin;
		$this->messageTitle = Title::newMainPage();
	}

	private function parseNavItemLine(string $line) {
		$services = MediaWikiServices::getInstance();
		$urlUtils = $services->getUrlUtils();
		$config = $this->skin->getConfig();

		$lineSegments = array_map('trim', explode('|', $line, 2));
		$link = $lineSegments[0];
		$label = sizeof($lineSegments) === 2 ? $lineSegments[1] : $lineSegments[0];

		if (!$link) {
			throw new Exception('Invalid navtext: Empty navigation lines are not allowed');
		}

		$id = strtr($label, ' ', '-');
		$id = Sanitizer::escapeIdForAttribute('na-' . $id);

		$msgLink = $this->skin->msg($link)->page($this->messageTitle)->inContentLanguage();
		if ($msgLink->exists()) {
			$link = $msgLink->text();
		}

		$msgLabel = $this->skin->msg($label)->page($this->messageTitle);
		if ($msgLabel->exists()) {
			$label = $msgLabel->text();
		}

		$linkAttributes = [];

		if (preg_match('/^(?i:' . $urlUtils->validProtocols() . ')/', $link)) {
			if (
				$config->get(MainConfigNames::NoFollowLinks) &&
				!$urlUtils->matchesDomainList(
					(string)$link,
			(array)$config->get(MainConfigNames::NoFollowDomainExceptions)
				)
			) {
				$linkAttributes['rel'] = 'nofollow';
			}

			if ($config->get(MainConfigNames::ExternalLinkTarget)) {
				$linkAttributes['target'] = $config->get(MainConfigNames::ExternalLinkTarget);
			}
		} else {
			$linkTitle = Title::newFromText($link);
			$link = $linkTitle ? $linkTitle->fixSpecialName()->getLinkURL() : '';
		}

		return array_merge([
			'text' => $label,
			'href' => $link,
			'id' => $id,
			'active' => false,
			'items' => [],
			'hasItems' => false,
		], $linkAttributes);
	}

	private function determineDepth(string $line) {
		$depth = 0;
		foreach (str_split($line) as $c) {
			if ($c === "*") {
				$depth++;
			} else {
				break;
			}
		}

		return $depth;
	}

	private function traverseNavbarTree(array &$navbar, array &$navItem, array $navTree) {
		$navItem = [ 'items' => &$navbar ];
		foreach ($navTree as $i) {
			$navItem = &$navItem['items'][$i];
		}
	}

	private function parseNavTextLines(array $lines, int &$cursor, int &$depth = 1) {
		$navItems = [];

		while ($cursor < sizeof($lines)) {
			$line = $lines[$cursor];

			$lineDepth = $this->determineDepth($line);
			if ($lineDepth <= 0) {
				// throw new Exception("Invalid navtext: Line did not start with *");
				$cursor++;
				continue;
			} else if ($lineDepth > ($depth + 1)) {
				// throw new Exception("Invalid navtext: Navigational depth is not continious");
				$cursor++;
				continue;
			} else if ($lineDepth < $depth) {
				$depth--;
				return $navItems;
			}

			$depth = $lineDepth;

			$line = trim($line, "* ");

			$newNavItem = $this->parseNavItemLine($line);

			if ($cursor < (sizeof($lines) - 1)) {
				$cursor++;

				$nextLine = $lines[$cursor];
				$nextDepth = $this->determineDepth($nextLine);
				
				if ($nextDepth > $depth) {
					$newNavItem['items'] = $this->parseNavTextLines($lines, $cursor, $depth);
					$newNavItem['hasItems'] = true;
				}

				$navItems[] = $newNavItem;
			} else {
				$navItems[] = $newNavItem;
				$cursor++;
			}
		}
		
		return $navItems;
	}

	private function getNavText() {
		return $this->skin->msg(NavParser::NAVBAR_DECLARATION_TITLE)->inContentLanguage()->plain();
	}

	public function parseNavbar() {
		$navText = $this->getNavText();
		if (!$navText) {
			return [];
		}

		$lines = explode("\n", $navText);

		$cursor = 0;
		return $this->parseNavTextLines($lines, $cursor);
	}
}
