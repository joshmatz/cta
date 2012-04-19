<?php 
class GameResourcesController extends AppController {
	var $name = 'GameResources';
	var $layout = 'admin';

	function beforeFilter(){
		parent::beforeFilter();
		//$this->Auth->allow('*');
		
	}
	
	function admin_index() {
		$this->set('resources', $this->paginate());
		// Populate GameRace fields
		$this->loadModel('GameRace');
		$this->set('GameRaceOptions', $this->GameRace->getGameRaceOptions(true));
		
		$this->loadModel('GameBuilding');
		$this->set('GameBuildingNames', $this->GameBuilding->getGameBuildingNames(null, null, true, true));
	}
	
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'resource'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('resource', $this->Resource->read(null, $id));
		
		
		// Populate GameRace fields
		$this->loadModel('GameRace');
		$this->set('GameRaceOptions', $this->GameRace->getGameRaceOptions(true));
		
		$this->loadModel('GameBuilding');
		$this->set('GameBuildingNames', $this->GameBuilding->getGameBuildingNames(null, null, true, true));
		
		$this->loadModel('GamePopulation');
		$this->set('GamePopulationNames', $this->GamePopulation->getGamePopulationNames(null, null, true, true));
		
		$this->loadModel('ResourceAssociation');
		$this->set('resourceAssocations', $this->ResourceAssociation->find('all', array('conditions' => array('resource_id' => $id))));
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'resource'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Resource->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'resource'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'resource'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Resource->read(null, $id);
		}	
			
		// Populate GameRace fields
		$this->loadModel('GameRace');
		$this->set('GameRaceOptions', $this->GameRace->getGameRaceOptions(true));
		
	}

	
	function admin_add() {
		if (!empty($this->data)) {
			$this->Resource->create();
			if ($this->Resource->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'resource'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'resource'));
			}
		}
		
		// Populate GameRace fields
		$this->loadModel('GameRace');
		$this->set('GameRaceOptions', $this->GameRace->getGameRaceOptions(true));
	}
	
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'resource'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Resource->delete($id)) {
			if(!$this->Resource->deleteAssociations($id))
				$this->Session->setFlash(sprintf(__('%s deleted', true), 'Resource'));
			else
				$this->Session->setFlash(sprintf(__('%s deleted but there was an error deleting the associations.', true), 'Resource'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Resource'));
		$this->redirect(array('action' => 'index'));
	}
	
}