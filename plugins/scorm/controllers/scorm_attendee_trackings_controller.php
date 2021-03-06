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
class ScormAttendeeTrackingsController extends ScormAppController {

	var $name = 'ScormAttendeeTrackings';
	
	function _setActiveCourse() {
		if (isset($this->params['named']['scorm'])) {
			$scorm = ClassRegistry::init('Scorm.Scorm');
			$this->activeCourse = $scorm->field('course_id',array('Scorm.id' => $this->params['named']['scorm']));
		}
	}

	function store_data() {
		Configure::write('debug',0);
		$params = $this->passedArgs;
		$data['student_id'] = $this->Auth->user('id');
		$data['sco_id'] = $params['sco'];
		foreach($this->params['form'] as $datamodel_element => $value) {
			$data['datamodel_element'] = $datamodel_element;
			$existant = $this->ScormAttendeeTracking->find($data);
			if ($existant) $data['id'] = $existant['ScormAttendeeTracking']['id'];
			else $this->ScormAttendeeTracking->create();
			$data['value'] = $value;
			$this->ScormAttendeeTracking->save($data);
		}
		die('ok');
	}

}
?>
