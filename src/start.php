<?php
/**
 * An auto-create account script for starting to use the interactive world.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    October, 31 2013
 * @link       http://ros.org/wiki/rms_interactive_world
 */

// start the session
session_start();

include_once('../api/users/user_accounts/user_accounts.inc.php');
include_once('../api/user_studies/experiments/experiments.inc.php');

$createStudy = True;
$sessionUser = null;
$exp = null;

// check if a user is logged in
if (isset($_SESSION['userid'])) {
    $sessionUser = user_accounts::get_user_account_by_id($_SESSION['userid']);
    
    // maybe they do have a study
    $exps = experiments::get_experiments_by_userid(
        $sessionUser['userid']
    );
    if ($exps) {
        foreach ($exps as $cur) {
            // grab the current timestamp from the SQL server
            $time = api::get_current_timestamp();
            if ($time >= $cur['start'] && $time <= $cur['end']) {
                $exp = $cur;
                break;
            }
        }
    }
    if (isset($exp)) {
        $createStudy = False;
    }
} else {
    // create the user
    $username = user_accounts::generate_salt();
    while (user_accounts::get_user_account_by_username($username)) {
        $username = user_accounts::generate_salt();
    }
    user_accounts::create_user_account(
        $username,
        user_accounts::generate_salt(),
        $username,
        $username,
        $username.'@'.$username.'.'.$username,
        'user'
    );
    // login our new user
    $sessionUser = user_accounts::get_user_account_by_username($username);
    $_SESSION['userid'] = $sessionUser['userid'];
}

if ($createStudy) {
    $rand = rand();
    $condition = -1;
    if ($rand % 2 === 0) {
        $condition = 13;
    } else {
        $condition = 14;
    }
    
    $sql = "INSERT INTO `experiments` (
             `userid`, `condid`, `envid`, `start`, `end`
              )
              VALUES (
                '".$sessionUser['userid']."',
                '".$condition."',
                '1',
                '".api::get_current_timestamp()."',
                '".date('Y-m-d H:i:s', strtotime("+30 minutes"))."'
              );";
    
    include_once('../inc/config.inc.php');
    mysqli_query($db, $sql);
    
    $exps = experiments::get_experiments_by_userid(
        $sessionUser['userid']
    );
    if ($exps) {
        foreach ($exps as $cur) {
            // grab the current timestamp from the SQL server
            $time = api::get_current_timestamp();
            if ($time >= $cur['start'] && $time <= $cur['end']) {
                $exp = $cur;
                break;
            }
        }
    }
}
sleep(1);
header('Location: ../connection/?expid='.$exp['expid'].'&intid=8&envid=1');
   