<?php
/**
 * Example PHP Framework
 * 
 * @package Framework
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */

namespace Framework\Model;

/**
 * Модель пользователей
 * 
 * @package Framework\Model
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Users {

	public function get($id) {
		// TODO get data from sql
		/*$result = mysql_query('
			SELECT
				`first`,
				`last`
			FROM
				`users`
			WHERE
				`id` = '.intval($id));
		return mysql_fetch_assoc($result);*/

		// возвращаем данные заглушки вместо реального запроса
		return array(
			'first' => 'Arnold',
            'last'  => 'Schwarzenegger'
		);
	}

}