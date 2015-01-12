<?php
/**
 * Interactive World Interface View
 *
 * The Interactive World interface view. This interface will allow for access to the interactive world.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/InteractiveWorldInterface
 * @since		InteractiveWorldInterface v 0.1.0
 * @version		0.3.1
 * @package		app.View.InteractiveWorldInterface
 */
?>

<script src="//s3.amazonaws.com/cdn.robotwebtools.org/EventEmitter2/current/eventemitter2.min.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/three.min.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/threejs/current/ColladaLoader.min.js"></script>
<script src="//s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/interactiveworld.min.js"></script>

<?php
// setup any study information
echo $this->Rms->initStudy();

if (isset($appointment)) {
	// experiment type
	$task = str_replace('Interactive World Task ', '', $appointment['Slot']['Condition']['name']);
} else {
	$task = rand(0, 2);
}

// completion code
$code = '';
$code .= rand(1, 9999);
$code .= '.';
$code .= rand(1, 9999);
?>

<script>
	// use the CDN versions
	INTERACTIVEWORLD.PATH = 'https://s3.amazonaws.com/resources.robotwebtools.org/';
	INTERACTIVEWORLD.CSS_PATH = 'https://s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/';
	INTERACTIVEWORLD.CSS = 'interactiveworld.min.css';
	INTERACTIVEWORLD.IMAGE_PATH = 'https://s3.amazonaws.com/cdn.robotwebtools.org/interactiveworldjs/current/';
	INTERACTIVEWORLD.NEXT_ARROW = 'next.png';
	INTERACTIVEWORLD.PREVIOUS_ARROW = 'previous.png';
	var viewer = INTERACTIVEWORLD.init({task: <?php echo $task; ?>});
	viewer.on('addition', function(event) {
		RMS.logJson('place', JSON.stringify(event));
	});
	viewer.on('completion', function(event) {
		var code = '<?php echo $code; ?>';
		RMS.logJson('completion', JSON.stringify({completion : code}));
		alert('Validation Code: ' + code);
	});
	RMS.logJson('config', JSON.stringify(viewer.config));
</script>
