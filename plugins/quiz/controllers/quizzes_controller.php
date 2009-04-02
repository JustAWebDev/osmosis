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
class QuizzesController extends QuizAppController {

	var $name = 'Quizzes';
	var $helpers = array('Html', 'Form' );
	/**
	 * question_types: used on the list of available question types
	 */	
	var $question_types = null;
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->question_types = array(
			'choice_question'	=> __('Choice Question', true),
			// 'cloze_question'	=> __('Cloze Question', true),
			'matching_question'	=> __('Matching Question', true),
			'ordering_question'=> __('Ordering Question', true),
			'text_question'		=> __('Text Question', true)
		);
		$this->set('question_types', $this->question_types);
	}
	
	function _setActiveCourse() {
		$actions = array('view','edit','rename','add_question','answer','delete');
		if (in_array($this->action,$actions) && isset ($this->params['pass'][0])) {
			$course_id = $this->Quiz->field('course_id',array('Quiz.id' => $this->params['pass'][0]));
			if (!empty($course_id))
				$this->activeCourse = $course_id;
		} else
			parent::_setActiveCourse();
	}
	
	function index() {
		$this->Quiz->recursive = 0;
		$this->set('quizzes', $this->paginate(array('Quiz.course_id' => $this->activeCourse)));
		$this->set('course_id',$this->activeCourse);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Quiz',true), 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->Quiz->recursive = 2;
		$this->set('quiz', $this->Quiz->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Quiz->create();
			$this->data['Quiz']['member_id'] = $this->Auth->user('id');
			if ($this->Quiz->save($this->data)) {
				$this->Session->setFlash(__('The Quiz has been saved',true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'edit', $this->Quiz->id,'course_id' => $this->data['Quiz']['course_id']));
			} else {
				$this->Session->setFlash(__('The Quiz could not be saved. Please, try again.',true), 'default', array('class' => 'error'));
			}
		}
		$this->set('course_id',$this->activeCourse);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Quiz',true), 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'), null, true);
		}
		
		$question_type = 'all';
		if (isset($this->params['named']['question_type'])) {
			$question_type = $this->params['named']['question_type'];
		}
		$this->set('question_type', $question_type);
		$question_name = 'All';
		if (isset($this->question_types[$question_type])) {
			$question_name = $this->question_types[$question_type];
		}
		$this->set('question_name', $question_name);

		if ($question_type != 'all') {
			list($questions,$quiz) = $this->Quiz->getQuestions($question_type, $id);
		} else {
			list($questions,$quiz) = $this->Quiz->getQuestions($this->question_types, $id);
		}
		
		if (empty($this->data))
			$this->data = $quiz;
		$this->set('question_list', $questions);
		$this->set('course_id',$this->activeCourse);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Quiz',true), 'default', array('class' => 'error'));
			$this->redirect('/');
		}
		if ($this->Quiz->del($id)) {
			$this->Session->setFlash(__('Quiz deleted',true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index','course_id' => $this->activeCourse));
		}
	}
	
	function rename($id=null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Quiz',true), 'default', array('class' => 'error'));
			$this->redirect('/');
		}
		if (!empty($this->data)) {
			if ($this->Quiz->save($this->data)) {
				$this->Session->setFlash(__('The Quiz has been saved',true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index','course_id' => $this->activeCourse));
			} else {
				$this->Session->setFlash(__('The Quiz could not be saved. Please, try again.',true), 'default', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Quiz->read(null, $id);
		}
	}

	function add_question($id = null) {
		if (empty($this->data) || !$id) {
			$this->Session->setFlash(__('Invalid Quiz', true), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Quiz->id = $id;
			if ($this->Quiz->addQuestions($this->data)) {
				$this->Session->setFlash(__('The questions were added to the quiz.', true), 'default', array('class' => 'success'));
				$this->redirect(
					array('controller' => 'quizzes', 'action' => 'edit',$id,'course_id' => $this->activeCourse)
				);
			} else {
				$this->Session->setFlash(__('The questions could not be added to the quiz.', true), 'default', array('class' => 'error'));
				$this->redirect(
					array('controller' => 'quizzes', 'action' => 'edit', $id,'course_id' => $this->activeCourse)
				);
			}
		}
	}

	function remove_question($quizQuestion) {
		$this->Quiz->QuizQuestion->id = $quizQuestion;
		$question = $this->Quiz->QuizQuestion->read();
		if (!empty($question) && $this->Quiz->removeQuestion($quizQuestion)) {
			$this->Session->setFlash(__('The question was removed from the quiz.', true), 'default', array('class' => 'success'));
			$this->redirect(
				array('controller' => 'quizzes', 'action' => 'edit',$question['QuizQuestion']['quiz_id'],'course_id' => $this->activeCourse)
			);
		} else {
			$this->Session->setFlash(__('The questions could not be added to the quiz.', true), 'default', array('class' => 'error'));
			$this->redirect(
				array('controller' => 'quizzes', 'action' => 'index', 'course_id' => $this->activeCourse)
			);
		}
	}
	
	function answer($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Quiz', true), 'default', array('class' => 'error'));
			$this->redirect('/');
		}
		
		if (!empty($this->data)) {
			if ($this->Quiz->saveAnswers($id,$this->data,$this->Auth->user('id'))) {
				$this->Session->setFlash(__('You have completed the Quiz.', true), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index','course_id' => $this->activeCourse));
			}
		}
		
		$conditions = array('Quiz.id' => $id);
		$recursive = 2;
		$quiz = $this->Quiz->find('first',compact('conditions','recursive'));
		$this->set(compact('quiz'));
	}

}
?>