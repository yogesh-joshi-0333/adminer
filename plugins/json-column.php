<?php

/** Display JSON values as table in edit
* @link https://www.adminer.org/plugins/#use
* @author Jakub Vrana, https://www.vrana.cz/
* @author Martin Zeman (Zemistr), http://www.zemistr.eu/
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerJsonColumn extends Adminer\Plugin {
	private function testJson($value) {
		if ((substr($value, 0, 1) == '{' || substr($value, 0, 1) == '[') && ($json = json_decode($value, true))) {
			return $json;
		}
		return $value;
	}

	private function buildTable($json) {
		echo '<table style="margin:2px; font-size:100%;">';
		foreach ($json as $key => $val) {
			echo '<tr>';
			echo '<th>' . Adminer\h($key) . '</th>';
			echo '<td>';
			if (is_scalar($val) || $val === null) {
				if (is_bool($val)) {
					$val = $val ? 'true' : 'false';
				} elseif ($val === null) {
					$val = 'null';
				} elseif (!is_numeric($val)) {
					$val = '"' . Adminer\h(addcslashes($val, "\r\n\"")) . '"';
				}
				echo '<code class="jush-js">' . $val . '</code>';
			} else {
				$this->buildTable($val);
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	function editInput($table, $field, $attrs, $value) {
		$json = $this->testJson($value);
		if ($json !== $value) {
			$this->buildTable($json);
		}
	}

	protected $translations = array(
		'cs' => array('' => 'Hodnoty JSON v editaci zobrazí formou tabulky'),
		'de' => array('' => 'Zeigen Sie JSON-Werte als Tabelle in der Bearbeitung an'),
		'pl' => array('' => 'Wyświetl wartości JSON jako tabelę w edycji'),
		'ro' => array('' => 'Afișează valorile JSON sub formă de tabel în editare'),
		'ja' => array('' => 'JSON 値をテーブルとして編集画面に表示'),
	);
}
