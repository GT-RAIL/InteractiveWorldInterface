<?php
App::uses('InterfaceController', 'Controller');

/**
 * Interactive World Interface Controller
 *
 * The Interactive World interface controller. This interface will allow for access to the interactive world.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/InteractiveWorldInterface
 * @since		InteractiveWorldInterface v 0.1.0
 * @version		0.3.1
 * @package		app.Controller
 */
class InteractiveWorldInterfaceController extends InterfaceController {

/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public function view() {
		$this->layout = 'empty';
		// set the title of the HTML page
		$this->set('title_for_layout', 'The Interactive World');
	}
}
