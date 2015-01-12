<?php
/**
 * Interactive World Model Controller
 *
 * The Interactive World model controller. This interface will allow for access to models via JSON. Other functions
 * allow the visualization of the data in the Interactive World.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/InteractiveWorldInterface
 * @since		InteractiveWorldInterface v 0.2.0
 * @version		0.3.0
 * @package		app.Controller
 */
class IwmodelsController extends AppController {

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('RequestHandler', 'Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Iwmodel', 'Log', 'Slot', 'Appointment');

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated viewing of a single page
		parent::beforeFilter();
		$this->Auth->allow('view', 'noise', 'appointment', 'template');
	}

/**
 * The basic view action. Information is returned as JSON.
 *
 * @param int $conditionID The condition ID.
 * @throws NotFoundException Thrown if an entry with the given condition ID is not found.
 * @return null
 */
	public function view($conditionID) {
		$iwmodel = $this->Iwmodel->find('first', array('conditions' => array('Iwmodel.condition_id' => $conditionID)));

		if (!$iwmodel) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid condition identifier.');
		}

		// JSON response
		$this->response->type('json');
		$this->autoRender = false;
		echo json_encode($iwmodel);
	}

/**
 * The noise view action will visualize noise in the environment.
 *
 * @param int $conditionID The condition ID.
 * @throws NotFoundException Thrown if an entry with the given condition ID is not found.
 * @return null
 */
	public function noise($conditionID) {
		// grab the correct slots
		$slots = $this->Slot->find('all', array(
			'conditions' => array('Slot.condition_id' => $conditionID),
			'recursive' => -1,
			'fields' => array('Slot.id')
		));

		if (!$slots) {
			// no valid entry found for the given ID
			throw new NotFoundException('No slots found.');
		}

		// grab appointments
		$slotIds = array();
		foreach ($slots as $slot) {
			$slotIds[] = $slot['Slot']['id'];
		}
		$appointments = $this->Appointment->find('all', array(
			'conditions' => array('Appointment.slot_id' => $slotIds),
			'recursive' => -1,
			'fields' => array('Appointment.id')
		));

		if (!$appointments) {
			// no valid entry found for the given ID
			throw new NotFoundException('No appointments found.');
		}

		// grab logs
		$appointmentIds = array();
		foreach ($appointments as $appointment) {
			$appointmentIds[] = $appointment['Appointment']['id'];
		}
		$logs = $this->Log->find('all', array(
			'conditions' => array('Log.appointment_id' => $appointmentIds, 'Log.label' => 'place'),
			'recursive' => -1,
			'fields' => array('Log.entry')
		));

		if (!$logs) {
			// no valid entry found for the given ID
			throw new NotFoundException('No logs found for this condition.');
		}

		// fix the values
		foreach ($logs as $key => $log) {
			$logs[$key]['Log']['entry'] = str_replace('&quot;', '"', $logs[$key]['Log']['entry']);
		}

		$this->layout = 'empty';
		$this->set('logs', $logs);
		$this->set('numLogs', count($logs));
		$this->set('title_for_layout', 'The Interactive World Noise');
	}

/**
 * The appointment view action will visualize a single appointment placement setting.
 *
 * @param int $appointmentID The appointment ID.
 * @throws NotFoundException Thrown if an entry with the given condition ID is not found.
 * @return null
 */
	public function appointment($appointmentID) {
		$logs = $this->Log->find('all', array(
			'conditions' => array('Log.appointment_id' => $appointmentID, 'Log.label' => 'place'),
			'recursive' => -1,
			'fields' => array('Log.entry')
		));

		if (!$logs) {
			// no valid entry found for the given ID
			throw new NotFoundException('No logs found for this condition.');
		}

		// fix the values
		foreach ($logs as $key => $log) {
			$logs[$key]['Log']['entry'] = str_replace('&quot;', '"', $logs[$key]['Log']['entry']);
		}

		$this->layout = 'empty';
		$this->set('logs', $logs);
		$this->set('numLogs', count($logs));
		$this->set('title_for_layout', 'The Interactive World Appointment');
	}

/**
 * The template view action will visualize a single template for a given condition.
 *
 * @param int $conditionID The condition ID.
 * @throws NotFoundException Thrown if an entry with the given condition ID is not found.
 * @return null
 */
	public function template($conditionID) {
		$iwmodel = $this->Iwmodel->find('first', array('conditions' => array('Iwmodel.condition_id' => $conditionID)));

		if (!$iwmodel) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid condition identifier.');
		}

		$this->layout = 'empty';
		$this->set('iwmodel', $iwmodel);
		$this->set('title_for_layout', 'The Interactive World Template');
	}
}
