<?php
/**
 * Provider.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketMessage!
 * @subpackage     Application
 * @since          1.0.0
 *
 * @date           14.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsMessage\Application;

use Nette\Http\UrlScript;
use Nette\Utils;

use IPub;
use IPub\WebSockets\Application as WebSocketsApplication;
use IPub\WebSockets\Entities as WebSocketsEntities;
use IPub\WebSockets\Http as WebSocketsHttp;

/**
 * Classic WebSockets message application
 *
 * @package        iPublikuj:WebSocketMessage!
 * @subpackage     Application
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Application extends WebSocketsApplication\Application
{
	/**
	 * {@inheritdoc}
	 */
	public function onMessage(WebSocketsEntities\Clients\IClient $from, WebSocketsHttp\IRequest $httpRequest, string $message)
	{
		$url = $httpRequest->getUrl();

		try {
			$data = Utils\ArrayHash::from(Utils\Json::decode($message));

			// Override route if is set in message
			if ($data->offsetExists('route')) {
				$url->setPath(rtrim($url->getPath(), '/') . '/' . ltrim($data->offsetGet('route'), '/'));

				// Override data
				$message = $data->offsetGet('data');
			}

		} catch (Utils\JsonException $ex) {
			// Nothing to do here
		}

		$url->setQueryParameter('action', 'message');

		$httpRequest->setUrl($url);

		$this->printer->success(sprintf('New message was recieved from %s', $from->getId()));

		$response = $this->processMessage($httpRequest, [
			'client' => $from,
			'data'   => $message,
		]);

		if (!$response instanceof WebSocketsApplication\Responses\NullResponse) {
			/** @var WebSocketsEntities\Clients\IClient $client */
			foreach ($this->clientsStorage as $client) {
				$client->send($response);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubProtocols() : array
	{
		return [];
	}
}
