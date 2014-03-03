<?php
/**
 * An interface for the interactive world for use within RMS.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    October, 31 2013
 * @link       http://ros.org/wiki/rms_interactive_world
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    October, 31 2013
 */
class rms_interactive_world
{
    /**
     * Generate the HTML for the interface. All HTML is echoed.
     * @param robot_environment $re The associated robot_environment object for
     *     this interface
     */
    function generate($re)
    {
        // check if we are authorized
        if (!$re->authorized()) {
            robot_environments::create_error_page(
                'Invalid experiment for the current user.',
                $re->get_user_account()
            );
        } else {?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<?php $re->create_head()?>
<?php $re->create_study_head()?>
<script src="build/eventemitter2.min.js"></script>
<script src="build/threecolladaloader.min.js"></script>
<script src="build/interactiveworld.min.js"></script>
<link rel="stylesheet"
  type="text/css" href="resources/css/interactiveworld.min.css" />
<link rel="stylesheet" type="text/css"
  href="https://fonts.googleapis.com/css?family=Ubuntu:700" />
<script>
  function start() {
    <?php 
    	$exp = $re->get_experiment();
    	echo "var condid = ".$exp['condid'].";";
    ?>
    var task;
		if (condid === 13) {
			task = 0;
		} else if (condid === 14) {
			task = 1;
		}    
    var viewer = INTERACTIVEWORLD.init(task);
    viewer.on('addition', function(event) {
      studyLog(JSON.stringify(event));
    });
    viewer.on('completion', function() {
      <?php 
          $code = '';
          $code .= rand(1, 9999);
          $code .= '.';
          $code .= rand(1, 9999);
      ?>
      var code = '<?php echo $code?>';
      alert('Validation Code: ' + code);
      studyLog(JSON.stringify({
        completion : code
      }));
    });
  }
</script>
</head>
<body onload="start();"></body>
</html>
<?php
        }
    }
}
