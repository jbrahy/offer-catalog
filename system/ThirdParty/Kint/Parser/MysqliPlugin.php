<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Jonathan Vollebregt (jnvsor@gmail.com), Rokas Šleinius (raveren@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Kint\Parser;

use Kint\Object\BasicObject;
use Mysqli;

/**
 * Adds support for Mysqli object parsing.
 *
 * Due to the way mysqli is implemented in PHP, this will cause
 * warnings on certain Mysqli objects if screaming is enabled.
 */
class MysqliPlugin extends Plugin {
	// These 'properties' are actually globals
	protected $always_readable
		= array(
			'client_version' => TRUE, 'connect_errno' => TRUE, 'connect_error' => TRUE,
		);

	// These are readable on empty mysqli objects, but not on failed connections
	protected $empty_readable
		= array(
			'client_info' => TRUE, 'errno' => TRUE, 'error' => TRUE,
		);

	// These are only readable on connected mysqli objects
	protected $connected_readable
		= array(
			'affected_rows' => TRUE, 'error_list' => TRUE, 'field_count' => TRUE, 'host_info' => TRUE, 'info' => TRUE, 'insert_id' => TRUE, 'server_info' => TRUE, 'server_version' => TRUE, 'stat' => TRUE, 'sqlstate' => TRUE, 'protocol_version' => TRUE, 'thread_id' => TRUE, 'warning_count' => TRUE,
		);

	public function getTypes()
	{
		return array('object');
	}

	public function getTriggers()
	{
		return Parser::TRIGGER_COMPLETE;
	}

	public function parse(&$var, BasicObject &$o, $trigger)
	{
		if ( ! $var instanceof Mysqli)
		{
			return;
		}

		$connected = FALSE;
		$empty = FALSE;

		if (\is_string(@$var->sqlstate))
		{
			$connected = TRUE;
		} elseif (\is_string(@$var->client_info))
		{
			$empty = TRUE;
		}

		foreach ($o->value->contents as $key => $obj)
		{
			if (isset($this->connected_readable[$obj->name]))
			{
				if ( ! $connected)
				{
					continue;
				}
			} elseif (isset($this->empty_readable[$obj->name]))
			{
				if ( ! $connected && ! $empty)
				{
					continue;
				}
			} elseif ( ! isset($this->always_readable[$obj->name]))
			{
				continue;
			}

			if ('null' !== $obj->type)
			{
				continue;
			}

			$param = $var->{$obj->name};

			if (NULL === $param)
			{
				continue;
			}

			$base = BasicObject::blank($obj->name, $obj->access_path);

			$base->depth = $obj->depth;
			$base->owner_class = $obj->owner_class;
			$base->operator = $obj->operator;
			$base->access = $obj->access;
			$base->reference = $obj->reference;

			$o->value->contents[$key] = $this->parser->parse($param, $base);
		}
	}
}
