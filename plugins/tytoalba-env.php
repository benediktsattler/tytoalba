<?php

/** Mark the environment (production, staging, backup, …) of the current connection for the Tyto Alba theme
 *
 * Adds `env env-<name>` to <body class>; the theme turns that into a colored
 * strip with a label at the top of the screen and tinted breadcrumbs. Built-in
 * names: prod, staging, dev, backup. Any other name works with a custom hue.
 * Labels may contain {db}, {user} and {server}; with no database selected,
 * a trailing separator before {db} is dropped ("Backup · {db}" → "Backup").
 *
 * Rules are matched against "user@server/db" in order, the first match wins.
 * `*` and `?` are wildcards; a pattern without "@" only matches the server,
 * one without "/" matches every database. Adminer's default server ("") is
 * matched as "localhost".
 *
 * new AdminerTytoAlbaEnv(array(
 *     '*@db.example.com'      => 'prod',
 *     'backend@localhost'     => array('backup', 'Backup · {db}'),
 *     '*@localhost'           => 'backup',
 *     '*@10.0.0.5/shop_test'  => array('qa', 'QA', 280), // name, label, hue 0–360
 * ));
 *
 * @link https://github.com/benediktsattler/tytoalba
 * @author Benedikt Nierula
 * @license https://opensource.org/licenses/MIT MIT License
 */
class AdminerTytoAlbaEnv extends Adminer\Plugin
{
	/** @var array<string, string|array{0: string, 1?: string, 2?: int}> */ private $rules;
	/** @var array{0: string, 1?: string, 2?: int}|false|null */ private $match;

	/**
	 * @param array<string, string|array> $rules pattern => name or array(name, label, hue)
	 */
	function __construct(array $rules = array())
	{
		$this->rules = $rules;
	}

	function bodyClass()
	{
		$match = $this->match();
		if ($match) {
			echo " env env-" . $match[0];
		}
	}

	function head($dark = null)
	{
		$match = $this->match();
		if ($match && (isset($match[1]) || isset($match[2]))) {
			$css = "";
			if (isset($match[1])) {
				$label = strtr($match[1], array(
					'{db}' => Adminer\DB,
					'{user}' => $_GET["username"],
					'{server}' => (Adminer\SERVER === "" ? "localhost" : Adminer\SERVER),
				));
				$label = trim(preg_replace('~\s*[·•|:/–-]\s*$~u', '', $label));
				// hex escapes: a backslash before "<" would still leave "</style" in the HTML
				$label = strtr($label, array('\\' => '\\5c ', '"' => '\\22 ', '<' => '\\3c ', '>' => '\\3e ', "\n" => '\\a '));
				$css .= "--env-label: \"$label\"; ";
			}
			if (isset($match[2])) {
				$css .= "--env-h: " . intval($match[2]) . "; ";
			}
			// .env.env-<name> outranks the theme's built-in body.env-<name>
			echo "<style>body.env.env-$match[0] { $css}</style>\n";
		}
	}

	/** First rule matching the current connection, false if none
	 * @return array{0: string, 1?: string, 2?: int}|false
	 */
	private function match()
	{
		if ($this->match === null) {
			$this->match = false;
			if (isset($_GET["username"])) { // logged in; the login form has no connection yet
				$host = (Adminer\SERVER === "" ? "localhost" : Adminer\SERVER);
				$subject = strtolower("$_GET[username]@$host/" . Adminer\DB);
				foreach ($this->rules as $pattern => $env) {
					$pattern = strtolower($pattern);
					if (strpos($pattern, "@") === false) {
						$pattern = "*@$pattern";
					}
					if (strpos($pattern, "/") === false) {
						$pattern .= "/*";
					}
					if (fnmatch($pattern, $subject)) {
						$env = array_values((array) $env);
						$env[0] = preg_replace('~[^a-z0-9_-]~', '', strtolower($env[0]));
						$this->match = ($env[0] != "" ? $env : false);
						break;
					}
				}
			}
		}
		return $this->match;
	}

	protected $translations = array(
		'de' => array('' => 'Kennzeichnet die Umgebung der aktuellen Verbindung (Produktion, Backup, …) für das Tyto-Alba-Theme'),
	);
}
