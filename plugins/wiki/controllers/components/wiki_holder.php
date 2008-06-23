<?php
/* SVN FILE: $Id$ */
/**
 * Ósmosis LMS: <http://www.osmosislms.org/>
 * Copyright 2008, Ósmosis LMS
 *
 * This file is part of Ósmosis LMS.
 * Ósmosis LMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ósmosis LMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ósmosis LMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @filesource
 * @copyright		Copyright 2008, Ósmosis LMS
 * @link			http://www.osmosislms.org/
 * @package			org.osmosislms
 * @subpackage		org.osmosislms.app
 * @since			Version 2.0 
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 */
App::import('Component', 'PlaceholderData');

class WikiHolderComponent extends PlaceholderDataComponent {
	var $name = 'WikiHolder';
	var $auto = true;
	var $cache = false;
	var $useful_fields = array(
		'Entry' => array(
			'fields' =>  array('Entry.id', 'Entry.title','Entry.slug'),
			'contain' => false
		)
	);
	
	function courseToolbar() {
		return array('url' => array(
			'plugin' => 'wiki', 
			'controller' => 'wikis',
			'action' => 'view', 
			'course_id' =>$this->controller->_getActiveCourse()));
	}
	
	function pluginUpdates() {
		$modelLog = ClassRegistry::getObject('ModelLog');
		$user_courses = $modelLog->Member->courses($this->controller->Auth->user('id'));
		$user_courses = Set::extract('/Course/id', $user_courses);
		$logs = $modelLog->find('log',
			array('models' => $this->useful_fields,'plugin' => 'Wiki','course_id' => $user_courses)
		);
		return $logs;
	}
	
}
?>
