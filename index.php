<?php
/**
 * A basic interface to display 1 or more MJPEG streams and basic keyboard teleoperation control.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 13 2012
 * @package    api.robot_environments.interfaces.basic
 * @link       http://ros.org/wiki/rms
 */

/**
 * Generate the HTML for the interface. All HTML is echoed.
 * @param robot_environment $re The associated robot_environment object for this interface
 */
function generate($re) {
	// lets begin by checking if we have an MJPEG widget and a keyboard at the very least
	if(!$re->get_widgets_by_name('MJPEG Stream')) {
		create_error_page('No MJPEG streams found.', $re->get_user_account());
	} else if(!$teleop = $re->get_widgets_by_name('Keyboard Teleop')) {
		create_error_page('No Keyboard Teloperation settings found.', $re->get_user_account());
	} else if(!$re->authorized()) {
		create_error_page('Invalid experiment for the current user.', $re->get_user_account());
	} else { // here we can spit out the HTML for our interface?>
<!DOCTYPE html>
<html>
<head>
	<?php $re->create_head() // grab the header information ?>
<script type="text/javascript"
	src="https://raw.github.com/RobotWebTools/rosbagjs/groovy-devel/topiclogger.js"></script>
<title>Basic Teleop Interface</title>

	<?php $re->make_ros() // connect to ROS ?>

<script type="text/javascript">
  ros.on('error', function() {
  	alert('Lost communication with ROS.');
  });

  function start() {
    // initialize the run-stop widget
    var pr2RunStop = new PR2RunStop({
      ros : ros,
      divID : 'run-stop',
      size : 33
    });

    // initialize the logger widget
    var logger = new TopicLogger({
      ros : ros,
      divID : 'logger'
    });

    // create the global display
    var rmsDisplay = new RMSDisplay({
    	ros : ros,
    	divID : 'scene',
    	width : 800,
    	height : 600,
    	background : '#022056',
    	gridColor : '#9CCDFC'
    });
  }
</script>
</head>
<body onload="start()">
	<section id="interface">


		<table class="center">
			<tr>
				<td width="33%"><div id="run-stop"></div></td>
				<td><h1>PR2 Remote Demonstrations</h1></td>
			</tr>
			<tr>
				<td width="33%"><div id="control-widget">
						<div id="speed-container">
						<?php echo create_keyboard_teleop_with_slider($teleop[0])?>
						</div>
					</div></td>
				<td><div id="scene"></div></td>
			</tr>
			<tr>
				<td width="33%"><div id="south-west-widget">
						<?php echo create_multi_mjpeg_canvas_by_envid($re->get_envid(), 266, 200, 1)?>
					</div>
				</td>
				<td width="66%"></td>
			</tr>
		</table>
		<div id="logger"></div>
	</section>
</body>
</html>
<?php
  }
}
?>
