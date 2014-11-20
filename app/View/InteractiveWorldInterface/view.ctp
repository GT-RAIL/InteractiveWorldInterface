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
 * @version		0.1.0
 * @package		app.Controller
 */
?>

<script src="http://cdn.robotwebtools.org/EventEmitter2/current/eventemitter2.min.js"></script>
<script src="http://cdn.robotwebtools.org/threejs/current/three.min.js"></script>
<script src="http://cdn.robotwebtools.org/threejs/current/ColladaLoader.min.js"></script>
<script src="http://cdn.robotwebtools.org/interactiveworldjs/current/interactiveworld.min.js"></script>

<?php
// setup any study information
echo $this->Rms->initStudy();

// experiment type
$task = str_replace('Interactive World Task ', '', $appointment['Slot']['Condition']['name']);

// completion code
$code = '';
$code .= rand(1, 9999);
$code .= '.';
$code .= rand(1, 9999);
?>

<script>
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