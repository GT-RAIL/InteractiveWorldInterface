<?php
/**
 * Interactive World Noise View
 *
 * The Interactive World noise view displays noise in the environment per task.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/InteractiveWorldInterface
 * @since		InteractiveWorldInterface v 0.3.0
 * @version		0.2.1
 * @package		app.View.Iwmodels
 */
?>

<script src="//s3.amazonaws.com/cdn.robotwebtools.org/EventEmitter2/current/eventemitter2.min.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/three.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/ColladaLoader.min.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/interactiveworld.min.js"></script>

<script>
	// use the CDN versions
	INTERACTIVEWORLD.PATH = 'https://s3.amazonaws.com/resources.robotwebtools.org/';
	INTERACTIVEWORLD.CSS_PATH = 'https://s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/';
	INTERACTIVEWORLD.CSS = 'interactiveworld.min.css';
	INTERACTIVEWORLD.IMAGE_PATH = 'https://s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/';
	INTERACTIVEWORLD.NEXT_ARROW = 'next.png';
	INTERACTIVEWORLD.PREVIOUS_ARROW = 'previous.png';
	var viewer = INTERACTIVEWORLD.init({task: -1});
	var models = <?php echo $iwmodel['Iwmodel']['value']; ?>;
	viewer.displayTemplateFromModels(models.models);
</script>

