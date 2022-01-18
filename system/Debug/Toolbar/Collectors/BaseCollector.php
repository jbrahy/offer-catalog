<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Debug\Toolbar\Collectors;

use CodeIgniter\Debug\Exceptions;

/**
 * Base Toolbar collector
 */
class BaseCollector {
	/**
	 * Whether this collector has data that can
	 * be displayed in the Timeline.
	 *
	 * @var boolean
	 */
	protected $hasTimeline = FALSE;

	/**
	 * Whether this collector needs to display
	 * content in a tab or not.
	 *
	 * @var boolean
	 */
	protected $hasTabContent = FALSE;

	/**
	 * Whether this collector needs to display
	 * a label or not.
	 *
	 * @var boolean
	 */
	protected $hasLabel = FALSE;

	/**
	 * Whether this collector has data that
	 * should be shown in the Vars tab.
	 *
	 * @var boolean
	 */
	protected $hasVarData = FALSE;

	/**
	 * The 'title' of this Collector.
	 * Used to name things in the toolbar HTML.
	 *
	 * @var string
	 */
	protected $title = '';

	//--------------------------------------------------------------------

	/**
	 * Does this Collector have data that should be shown in the
	 * 'Vars' tab?
	 *
	 * @return boolean
	 */
	public function hasVarData(): bool
	{
		return (bool)$this->hasVarData;
	}

	//--------------------------------------------------------------------

	/**
	 * Gets a collection of data that should be shown in the 'Vars' tab.
	 * The format is an array of sections, each with their own array
	 * of key/value pairs:
	 *
	 *  $data = [
	 *      'section 1' => [
	 *          'foo' => 'bar,
	 *          'bar' => 'baz'
	 *      ],
	 *      'section 2' => [
	 *          'foo' => 'bar,
	 *          'bar' => 'baz'
	 *      ],
	 *  ];
	 */
	public function getVarData()
	{
		return NULL;
	}

	//--------------------------------------------------------------------

	/**
	 * Clean Path
	 *
	 * This makes nicer looking paths for the error output.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public function cleanPath(string $file): string
	{
		return Exceptions::cleanPath($file);
	}

	//--------------------------------------------------------------------

	/**
	 * Return settings as an array.
	 *
	 * @return array
	 */
	public function getAsArray(): array
	{
		return [
			'title' => $this->getTitle(), 'titleSafe' => $this->getTitle(TRUE), 'titleDetails' => $this->getTitleDetails(), 'display' => $this->display(), 'badgeValue' => $this->getBadgeValue(), 'isEmpty' => $this->isEmpty(), 'hasTabContent' => $this->hasTabContent(), 'hasLabel' => $this->hasLabel(), 'icon' => $this->icon(), 'hasTimelineData' => $this->hasTimelineData(), 'timelineData' => $this->timelineData(),
		];
	}

	//--------------------------------------------------------------------

	/**
	 * Gets the Collector's title.
	 *
	 * @param boolean $safe
	 *
	 * @return string
	 */
	public function getTitle(bool $safe = FALSE): string
	{
		if ($safe)
		{
			return str_replace(' ', '-', strtolower($this->title));
		}

		return $this->title;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns any information that should be shown next to the title.
	 *
	 * @return string
	 */
	public function getTitleDetails(): string
	{
		return '';
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the data of this collector to be formatted in the toolbar
	 *
	 * @return array|string
	 */
	public function display()
	{
		return [];
	}

	//--------------------------------------------------------------------

	/**
	 * Gets the "badge" value for the button.
	 */
	public function getBadgeValue()
	{
		return NULL;
	}

	//--------------------------------------------------------------------

	/**
	 * Does this collector have any data collected?
	 *
	 * If not, then the toolbar button won't get shown.
	 *
	 * @return boolean
	 */
	public function isEmpty(): bool
	{
		return FALSE;
	}

	//--------------------------------------------------------------------

	/**
	 * Does this collector need it's own tab?
	 *
	 * @return boolean
	 */
	public function hasTabContent(): bool
	{
		return (bool)$this->hasTabContent;
	}

	//--------------------------------------------------------------------

	/**
	 * Does this collector have a label?
	 *
	 * @return boolean
	 */
	public function hasLabel(): bool
	{
		return (bool)$this->hasLabel;
	}

	/**
	 * Returns the HTML to display the icon. Should either
	 * be SVG, or a base-64 encoded.
	 *
	 * Recommended dimensions are 24px x 24px
	 *
	 * @return string
	 */
	public function icon(): string
	{
		return '';
	}

	/**
	 * Does this collector have information for the timeline?
	 *
	 * @return boolean
	 */
	public function hasTimelineData(): bool
	{
		return (bool)$this->hasTimeline;
	}

	/**
	 * Grabs the data for the timeline, properly formatted,
	 * or returns an empty array.
	 *
	 * @return array
	 */
	public function timelineData(): array
	{
		if ( ! $this->hasTimeline)
		{
			return [];
		}

		return $this->formatTimelineData();
	}

	/**
	 * Child classes should implement this to return the timeline data
	 * formatted for correct usage.
	 *
	 * Timeline data should be formatted into arrays that look like:
	 *
	 *  [
	 *      'name'      => 'Database::Query',
	 *      'component' => 'Database',
	 *      'start'     => 10       // milliseconds
	 *      'duration'  => 15       // milliseconds
	 *  ]
	 *
	 * @return array
	 */
	protected function formatTimelineData(): array
	{
		return [];
	}
}
