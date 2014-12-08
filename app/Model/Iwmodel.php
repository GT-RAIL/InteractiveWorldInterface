<?php
/**
 * Interactive World Model
 *
 * An interactive world model is linked to a condition and contains the JSON message of the models.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		InteractiveWorldInterface v 0.2.0
 * @version		0.2.0
 * @package		app.Model
 */
class Iwmodel extends AppModel {

/**
 * The validation criteria for the model.
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid ID.',
				'required' => 'update'
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'IDs must be greater than 0.',
				'required' => 'update'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This user ID already exists.',
				'required' => 'update'
			)
		),
		'condition_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid condition ID.',
				'required' => true
			),
			'gt' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'Condition IDs must be greater than 0.',
				'required' => true
			)
		),
		'value' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid start value.',
				'required' => true
			)
		),
		'created' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid creation time.',
				'required' => 'create'
			)
		),
		'modified' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a valid modification time.',
				'required' => true
			)
		)
	);

/**
 * All models belong to a single condition.
 *
 * @var string
 */
	public $belongsTo = 'Condition';
}
