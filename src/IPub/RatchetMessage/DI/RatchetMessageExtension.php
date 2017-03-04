<?php
/**
 * WebSocketsMessageExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketMessage!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           01.03.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsMessage\DI;

use Nette;
use Nette\DI;

use IPub;
use IPub\WebSocketsMessage;
use IPub\WebSocketsMessage\Application;

/**
 * WebSockets WAMP extension container
 *
 * @package        iPublikuj:WebSocketMessage!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method DI\ContainerBuilder getContainerBuilder()
 * @method array getConfig(array $default)
 * @method string prefix($id)
 */
final class WebSocketsMessageExtension extends DI\CompilerExtension
{
	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();

		/** @var DI\ContainerBuilder $builder */
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('application'))
			->setClass(Application\Application::class);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'websocketsMessage')
	{
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new WebSocketsMessageExtension());
		};
	}
}
