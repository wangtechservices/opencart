<?php
namespace Opencart\System\Library\Template;
class Twig {
	protected $loader;
	protected $data = [];

	public function __construct($directory = '') {
		$this->loader = new \Twig\Loader\FilesystemLoader(DIR_TEMPLATE);

		$this->loader->addPath(DIR_EXTENSION . 'opencart/admin/view/template/');
		$this->loader->addPath(DIR_EXTENSION . 'opencart/catalog/view/template/');
	}

	public function addPath($directory) {
		$this->loader->addPath($directory);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $code = '') {
		/*
		 * FYI all the Twig lovers out there!
		 * The Twig syntax is good, but the implementation and the available methods is a joke!
		 *
		 * All the Symfony developer has done is create a garbage frame work putting 3rd party scripts into DI containers.
		 * The Twig syntax he ripped off from Jinja and Django templates then did a garbage implementation!
		 *
		 * The fact that this system cache is just compiling php into more php code instead of html is a disgrace!
		 */

		// render from modified template code
		// Initialize Twig environment
		$config = [
			'charset'     => 'utf-8',
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		];

		if ($code) {
			$loader = new \Twig\Loader\ArrayLoader([$filename . '.twig' => $code]);
		} else {
			$loader = $this->loader;
		}

		if (substr($filename, 0, 19) == 'extension/opencart/') {
			$filename = substr($filename, 19);
		}

		if (substr($filename, 0, 9) == 'extension') {
			//$filename = substr($filename, 19);
		}

		try {
			$twig = new \Twig\Environment($loader, $config);

			return $twig->render($filename . '.twig', $this->data);
		} catch (Twig_Error_Syntax $e) {
			error_log('Error: Could not load template ' . $filename . '!');
			exit();
		}
	}

}