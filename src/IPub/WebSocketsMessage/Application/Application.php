<?php
/**
 * Provider.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsMessage!
 * @subpackage     Application
 * @since          1.0.0
 *
 * @date           14.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsMessage\Application;

use Nette\Utils;

use IPub;
use IPub\WebSockets\Application as WebSocketsApplication;
use IPub\WebSockets\Entities as WebSocketsEntities;
use IPub\WebSockets\Http as WebSocketsHttp;

/**
 * Classic WebSockets message application
 *
 * @package        iPublikuj:WebSocketsMessage!
 * @subpackage     Application
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Application extends WebSocketsApplication\Application
{
	/**
	 * {@inheritdoc}
	 */
	public function handleMessage(WebSocketsEntities\Clients\IClient $from, WebSocketsHttp\IRequest $httpRequest, string $message)
	{
		parent::handleMessage($from, $httpRequest, $message);

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

		$parsedAction = $url->getQueryParameter(WebSocketsApplication\Controller\Controller::ACTION_KEY, WebSocketsApplication\Controller\Controller::DEFAULT_ACTION);

		if ($parsedAction === WebSocketsApplication\Controller\Controller::DEFAULT_ACTION) {
			$url->setQueryParameter(WebSocketsApplication\Controller\Controller::ACTION_KEY, 'message');
		}

		$httpRequest->setUrl($url);

		$this->logger->debug(sprintf('New message was recieved from %s', $from->getId()));

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
