<?php
/**
 * CallResponse.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsMessage!
 * @subpackage     Responses
 * @since          1.0.0
 *
 * @date           14.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsMessage\Application\Responses;

use Nette;
use Nette\Utils;

use IPub;
use IPub\WebSockets\Application as WebSocketsApplication;

/**
 * Common response for call method in client side
 *
 * @package        iPublikuj:WebSocketsMessage!
 * @subpackage     Responses
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @author         Vít Ledvinka, frosty22 <ledvinka.vit@gmail.com>
 */
class CallResponse implements WebSocketsApplication\Responses\IResponse
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * Name of method
	 *
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @param string $name
	 * @param array $data
	 */
	public function __construct(string $name, array $data = [])
	{
		$this->name = $name;
		$this->data = $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create() : string
	{
		return Utils\Json::encode((object) [
			'type' => 'call',
			'name' => $this->name,
			'data' => $this->data
		]);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->create();
	}
}
