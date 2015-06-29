<?php
namespace Polyglot\Localization\Services;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Polyglot\Localization\Exceptions\CompilationException;

abstract class AbstractService
{
	/**
	 * The Container
	 *
	 * @var Container
	 */
	protected $app;

	/**
	 * The command executing compilation
	 *
	 * @var Command
	 */
	protected $command;

	/**
	 * Build a new Compiler
	 *
	 * @param Container $app
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;
	}

	/**
	 * Set the Command executing the compilation
	 *
	 * @param Command $command
	 */
	public function setCommand(Command $command)
	{
		$this->command = $command;
	}

	/**
	 * Sprintf and execute a command
	 *
	 * @param string $message
	 * @param string $parameters ...
	 *
	 * @return int
	 */
	protected function execf()
	{
		$arguments = func_get_args();
		$message   = array_pull($arguments, 0);
		$command   = vsprintf($message, $arguments);

		// Pipe the ouput of `$command` into `$output`
		exec($command.' 2>&1', $output, $result);

		if ($result > 0) {
			throw new CompilationException(implode(PHP_EOL, $output));
		}
	}
}
