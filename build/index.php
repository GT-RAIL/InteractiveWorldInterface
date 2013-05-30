<?php
/**
 * An interface for the interactive world for use within RMS.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    May, 22 2013
 * @link       http://ros.org/wiki/rms_interactive_world
 */

/**
 * A static class to contain the interface generate function.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    May, 22 2013
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
        // check if we have enough valid widgets
        if (!$streams = $re->get_widgets_by_name('MJPEG Stream')) {
            robot_environments::create_error_page(
                'No MJPEG streams found.',
                $re->get_user_account()
            );
        } else if (!$teleop = $re->get_widgets_by_name('Keyboard Teleop')) {
            robot_environments::create_error_page(
                'No Keyboard Teloperation settings found.',
                $re->get_user_account()
            );
        } else if (!$im = $re->get_widgets_by_name('Interactive Markers')) {
            robot_environments::create_error_page(
                'No Interactive Marker settings found.',
                $re->get_user_account()
            );
        } else if (!$nav = $re->get_widgets_by_name('2D Navigation')) {
            robot_environments::create_error_page(
                'No 2D Navaigation settings found.',
                $re->get_user_account()
            );
        } else if (!$re->authorized()) {
            robot_environments::create_error_page(
                'Invalid experiment for the current user.',
                $re->get_user_account()
            );
        } else {
            // lets create a string array of MJPEG streams
            $topics = '[';
            $labels = '[';
            foreach ($streams as $s) {
                $topics .= "'".$s['topic']."', ";
                $labels .= "'".$s['label']."', ";
            }
            $topics = substr($topics, 0, strlen($topics) - 2).']';
            $labels = substr($labels, 0, strlen($topics) - 2).']';

            // we will also need the map
            $widget = widgets::get_widget_by_table('maps');
            $map = widgets::get_widget_instance_by_widgetid_and_id(
                $widget['widgetid'], $nav[0]['mapid']
            );

            $collada = 'ColladaAnimationCompress/0.0.1/ColladaLoader2.min.js'?>
<!DOCTYPE html>
<html>
<head>
<?php $re->create_head() // grab the header information ?>
<title><?php echo $title = 'Interactive World' ?></title>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/threejs/r56/three.min.js">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/<?php echo $collada?>">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/mjpegcanvasjs/r1/mjpegcanvas.min.js">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/keyboardteleopjs/r1/keyboardteleop.min.js">
</script>
<script type="text/javascript"
  src="http://cdn.robotwebtools.org/ros3djs/r4/ros3d.min.js">
</script>

<script type="text/javascript">
  //connect to ROS
  var ros = new ROSLIB.Ros({
    url : '<?php echo $re->rosbridge_url()?>'
  });

  ros.on('error', function() {
    alert('Lost communication with ROS.');
  });

  var editMode = false;
  var templates = 0;

  /**
   * Change the mode of the interface.
   */
  function changeMode() {
	var saveButton = $('#save');
	var loadButton = $('#load');
	  
    if(!editMode) {
       saveButton.removeAttr('disabled').removeClass('ui-state-disabled');
       loadButton.removeAttr('disabled').removeClass('ui-state-disabled');
    } else {
       saveButton.attr('disabled', 'disabled').addClass('ui-state-disabled');
       loadButton.attr('disabled', 'disabled').addClass('ui-state-disabled');
    }
    editMode = !editMode;
  }

  /**
   * Load everything on start.
   */
  function start() {
    // create MJPEG streams
    new MJPEGCANVAS.MultiStreamViewer({
      divID : 'video1',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 400,
      height : 300,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>
    });
    new MJPEGCANVAS.MultiStreamViewer({
      divID : 'video2',
      host : '<?php echo $re->get_mjpeg()?>',
      port : '<?php echo $re->get_mjpegport()?>',
      width : 400,
      height : 300,
      topics : <?php echo $topics?>,
      labels : <?php echo $labels?>,
      defaultStream : <?php echo min(count($streams), 1) ?>
    });

    // initialize the teleop
    new KEYBOARDTELEOP.Teleop({
      ros : ros,
      topic : '<?php echo $teleop[0]['twist']?>',
      throttle : '<?php echo $teleop[0]['throttle']?>'
    });

    // create the main viewer
    var viewer = new ROS3D.Viewer({
      divID : 'scene',
      width : 1180,
      height : 600,
      antialias : true
    });

    // setup a client to listen to TFs
    var tfClient = new ROSLIB.TFClient({
      ros : ros,
      angularThres : 0.01,
      transThres : 0.01,
      rate : 10.0,
      fixedFrame : '<?php echo $im[0]['fixed_frame'] ?>'
    });
    
    var grid = new ROS3D.OccupancyGridClient({
      ros : ros,
      rootObject : viewer.scene,
      topic : '<?php echo $map['topic']?>',
      tfClient : tfClient
    });
    grid.on('change', function() {
      // change the opacity level
      grid.currentGrid.children[0].material.transparent = true;
      grid.currentGrid.children[0].material.opacity = 0.7;
      grid.currentGrid.children[0].material.needsUpdate = true;
    });

    // setup the URDF client
    new ROS3D.UrdfClient({
      ros : ros,
      tfClient : tfClient,
      path : 'http://resources.robotwebtools.org/',
      rootObject : viewer.scene
    });

    // setup the marker clients
    <?php
    foreach ($im as $cur) {?>
      new ROS3D.InteractiveMarkerClient({
        ros : ros,
        tfClient : tfClient,
        topic : '<?php echo $cur['topic'] ?>',
        camera : viewer.camera,
        rootObject : viewer.selectableObjects,
        path : 'http://resources.robotwebtools.org/'
      });
    <?php 
    }
    ?>

    // load the Willow model
    var willow = new ROS3D.SceneNode({
      tfClient : tfClient,
      frameID : '/map',
      object : new ROS3D.MeshResource({
        path : 'http://resources.robotwebtools.org/models/willow_garage/',
        resource : 'willow.dae'
      }),
      pose : new ROSLIB.Pose({
        position : {
          x : 18.5,
          y : 66.75,
          z : 0
        },
        orientation : {
          x : 0,
          y : 0,
          z : -0.83602597651332,
          w : 0.5486898637618064
        }
      })
    });
    viewer.addObject(willow);

    // keep the camera centered at the head
    tfClient.subscribe('/head_mount_kinect_rgb_link', function(tf) {
      viewer.cameraControls.center.x = tf.translation.x;
      viewer.cameraControls.center.y = tf.translation.y;
      viewer.cameraControls.center.z = tf.translation.z;
    });

    // create the buttons
    var recognize = new ROSLIB.ActionClient({
      ros : ros,
      serverName : '/object_detection_user_command',
      actionName : 'pr2_interactive_object_detection/UserCommandAction'
    });
    var recognizeButton = $('#recognize');
    recognizeButton.button().click(function() {
      var goal = new ROSLIB.Goal({
        actionClient : recognize,
        goalMessage : {
          request : 1,
          interactive : false
        }
      });
      goal.on('result', function(result) {
        var goal2 = new ROSLIB.Goal({
          actionClient : recognize,
          goalMessage : {
            request : 2,
            interactive : false
          }
        });
        goal2.on('result', function(result) {});
        goal2.send();
      });
      goal.send();
    });
    var change = new ROSLIB.Service({
      ros : ros,
      name : '/interactive_world_server/change_mode',
      serviceType : 'std_srvs/Empty'
    });
    var changeButton = $('#change');
    changeButton.button().click(function() {
      change.callService(new ROSLIB.ServiceRequest(), function(result) {
        changeMode();
      });
    });

    var save = new ROSLIB.Service({
      ros : ros,
      name : '/interactive_world_server/save',
      serviceType : 'std_srvs/Empty'
    });
	var saveButton = $('#save');
	saveButton.button().click(function() {
      save.callService(new ROSLIB.ServiceRequest(), function(result) {});
    });
	saveButton.attr('disabled', 'disabled').addClass('ui-state-disabled');
	var templateCount = new ROSLIB.Topic({
	  ros : ros,
	  name : '/interactive_world_server/template_count',
	  messageType : 'std_msgs/Int32'
	});
	templateCount.subscribe(function(count) {
  	  templates = count.data;
	});
	$('#dialog').dialog({
      autoOpen: false,
      show: {
        effect: 'blind',
		duration: 1000
	   },
	   hide: {
		 effect: 'explode',
		 duration: 500
	   }
	});
	var load = new ROSLIB.Topic({
      ros : ros,
      name : '/interactive_world_server/load',
      messageType : 'std_msgs/Int32'
    });
	var loadButton = $('#load');
	loadButton.button().click(function() {
	  dialog = $('#dialog');
	  dialog.html('');
	  for (var i=0; i<templates; i++) {
        var button = $(document.createElement('button'));
        var num = i+1;
        button.attr('name', num);
        button.html('Template ' + num);
        button.button().click(num, function(e) {
          load.publish(new ROSLIB.Message({data:e.data}));
          dialog.dialog('close');
        });  
        dialog.append(button);
	  }
	  dialog.dialog('open');
	});
    loadButton.attr('disabled', 'disabled').addClass('ui-state-disabled');
    
    // setup the buttons
    $('body').bind('DOMSubtreeModified', function() {
      $('button').button();
    });
  }
</script>
</head>
<body onload="start();">
  <section class="interface">
    <table>
      <tr>
        <td rowspan="2">
          <div id="button-container">
            <center>
              <button class="recognize" id="recognize">Recognize</button>
              <button class="change" id="change">Change Mode</button>
              <br /><br />
                <button class="save" id="save">Save</button>
                <button class="load" id="load">Load</button>
              <br /><br />
            </center>
          </div>
          <div class="mjpeg-widget" id="video1"></div>
          <div class="mjpeg-widget" id="video2"></div>
        </td>
        <td><h2>
            <?php echo $title?>
          </h2></td>
        <td align="right">></td>
      </tr>
      <tr>
        <td colspan="2"><div id="scene" class="scene"></div></td>
      </tr>
    </table>
    <?php content::create_footer()?>
  </section>
  <div id="dialog" title="Template Loader"></div>
</body>
</html>
<?php
        }
    }
}
