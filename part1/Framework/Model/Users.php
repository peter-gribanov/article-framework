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
		return array(
			'first' => 'Arnold',
            'last'  => 'Schwarzenegger'
		);
	}

}