<?php
class TextQuestionsController extends AppController {

	var $name = 'TextQuestions';
	var $helpers = array('Html', 'Form' );

	function index() {
		$this->TextQuestion->recursive = 0;
		$this->set('textQuestions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid Text Question.');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('textQuestion', $this->TextQuestion->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->cleanUpFields();
			$this->TextQuestion->create();
			if ($this->TextQuestion->save($this->data)) {
				$this->Session->setFlash('The Text Question has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Text Question could not be saved. Please, try again.');
			}
		}
		$quizzes = $this->TextQuestion->Quiz->generateList();
		$this->set(compact('quizzes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Text Question');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			$this->cleanUpFields();
			if ($this->TextQuestion->save($this->data)) {
				$this->Session->setFlash('The Text Question has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Text Question could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TextQuestion->read(null, $id);
		}
		$quizzes = $this->TextQuestion->Quiz->generateList();
		$this->set(compact('quizzes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Text Question');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->TextQuestion->del($id)) {
			$this->Session->setFlash('Text Question #'.$id.' deleted');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}

}
?>