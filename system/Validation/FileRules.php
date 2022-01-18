<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Validation;

use CodeIgniter\HTTP\RequestInterface;
use Config\Mimes;
use Config\Services;

/**
 * File validation rules
 */
class FileRules {
	/**
	 * Request instance. So we can get access to the files.
	 *
	 * @var RequestInterface
	 */
	protected $request;

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 *
	 * @param RequestInterface $request
	 */
	public function __construct(RequestInterface $request = NULL)
	{
		if (is_null($request))
		{
			$request = Services::request();
		}

		$this->request = $request;
	}

	//--------------------------------------------------------------------

	/**
	 * Verifies that $name is the name of a valid uploaded file.
	 *
	 * @param string|null $blank
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function uploaded(?string $blank, string $name): bool
	{
		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if (ENVIRONMENT === 'testing')
			{
				if ($file->getError() !== 0)
				{
					return FALSE;
				}
			} else
			{
				// Note: cannot unit test this; no way to over-ride ENVIRONMENT?
				// @codeCoverageIgnoreStart
				if ( ! $file->isValid())
				{
					return FALSE;
				}
				// @codeCoverageIgnoreEnd
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------

	/**
	 * Verifies if the file's size in Kilobytes is no larger than the parameter.
	 *
	 * @param string|null $blank
	 * @param string $params
	 *
	 * @return boolean
	 */
	public function max_size(?string $blank, string $params): bool
	{
		// Grab the file name off the top of the $params
		// after we split it.
		$params = explode(',', $params);
		$name = array_shift($params);

		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if ($file->getError() === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}

			if ($file->getError() === UPLOAD_ERR_INI_SIZE)
			{
				return FALSE;
			}

			if ($file->getSize() / 1024 > $params[0])
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------

	/**
	 * Uses the mime config file to determine if a file is considered an "image",
	 * which for our purposes basically means that it's a raster image or svg.
	 *
	 * @param string|null $blank
	 * @param string $params
	 *
	 * @return boolean
	 */
	public function is_image(?string $blank, string $params): bool
	{
		// Grab the file name off the top of the $params
		// after we split it.
		$params = explode(',', $params);
		$name = array_shift($params);

		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if ($file->getError() === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}

			// We know that our mimes list always has the first mime
			// start with `image` even when then are multiple accepted types.
			$type = Mimes::guessTypeFromExtension($file->getExtension());

			if (mb_strpos($type, 'image') !== 0)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------

	/**
	 * Checks to see if an uploaded file's mime type matches one in the parameter.
	 *
	 * @param string|null $blank
	 * @param string $params
	 *
	 * @return boolean
	 */
	public function mime_in(?string $blank, string $params): bool
	{
		// Grab the file name off the top of the $params
		// after we split it.
		$params = explode(',', $params);
		$name = array_shift($params);

		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if ($file->getError() === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}

			if ( ! in_array($file->getMimeType(), $params, TRUE))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------

	/**
	 * Checks to see if an uploaded file's extension matches one in the parameter.
	 *
	 * @param string|null $blank
	 * @param string $params
	 *
	 * @return boolean
	 */
	public function ext_in(?string $blank, string $params): bool
	{
		// Grab the file name off the top of the $params
		// after we split it.
		$params = explode(',', $params);
		$name = array_shift($params);

		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if ($file->getError() === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}

			if ( ! in_array($file->guessExtension(), $params, TRUE))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------

	/**
	 * Checks an uploaded file to verify that the dimensions are within
	 * a specified allowable dimension.
	 *
	 * @param string|null $blank
	 * @param string $params
	 *
	 * @return boolean
	 */
	public function max_dims(?string $blank, string $params): bool
	{
		// Grab the file name off the top of the $params
		// after we split it.
		$params = explode(',', $params);
		$name = array_shift($params);

		if ( ! ($files = $this->request->getFileMultiple($name)))
		{
			$files = [$this->request->getFile($name)];
		}

		foreach ($files as $file)
		{
			if (is_null($file))
			{
				return FALSE;
			}

			if ($file->getError() === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}

			// Get Parameter sizes
			$allowedWidth = $params[0] ?? 0;
			$allowedHeight = $params[1] ?? 0;

			// Get uploaded image size
			$info = getimagesize($file->getTempName());
			$fileWidth = $info[0];
			$fileHeight = $info[1];

			if ($fileWidth > $allowedWidth || $fileHeight > $allowedHeight)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------
}
