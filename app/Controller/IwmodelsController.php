<?php
/**
 * Interactive World Model Controller
 *
 * The Interactive World model controller. This interface will allow for access to models via JSON.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/InteractiveWorldInterface
 * @since		InteractiveWorldInterface v 0.2.0
 * @version		0.2.1
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
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated viewing of a single page
		parent::beforeFilter();
		$this->Auth->allow('view');
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
}
