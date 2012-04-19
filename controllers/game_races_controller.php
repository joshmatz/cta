<?php 
/*
	Currently unnecessary.
*/
class GameRacesController extends AppController {
	var $name = 'GameRaces';
	var $layout = 'admin';
	
	function beforeFilter(){
		parent::beforeFilter();
		//$this->Auth->allow('*');
		
	}
	
	function admin_index() {
		debug($this->GameRace->getRaces());
		$this->set('races', $this->paginate());
		
	}
	
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'race'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('race', $this->GameRace->read(null, $id));
		
		
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'race'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->GameRace->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'race'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'race'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->GameRace->read(null, $id);
		}	
		
	}

	
	function admin_add() {
		if (!empty($this->data)) {
			$this->GameRace->create();
			if ($this->GameRace->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'race'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'race'));
			}
		}
		
	}
	
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'race'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Resource->delete($id)) {
			if(!$this->GameRace->deleteAssociations($id))
				$this->Session->setFlash(sprintf(__('%s deleted', true), 'Race'));
			else
				$this->Session->setFlash(sprintf(__('%s deleted but there was an error deleting the associations.', true), 'Race'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Race'));
		$this->redirect(array('action' => 'index'));
	}
	
}