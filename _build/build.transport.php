<?php
/**
 * GetMyComponent Build Script
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 *
 * GetMyComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * GetMyComponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * GetMyComponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package GetMyComponent
 * @subpackage build
 */
/**
 * GetMyComponent Package
 *
 * Description: Installs the files of the GetMyComponent build
 * script template.
 *
 * @package GetMyComponent
 * @subpackage build
 */


/* See the GetMyComponent/core/docs/notes.txt file for
 * more detailed information about using the package
 *
 * Search and replace tasks:
 * (edit the resource and element names first if they have
 * different names than your package.)
 *
 * GetMyComponent -> Name of your package
 * GetMyComponent -> lowercase name of your package
 * Your Name -> Your Actual Name
 * Your Site -> Name of your site
 * yoursite -> domain name of your site
 * you@yourdomain.com -> your email address
 * Description -> Description of file or component
 *
 * 1/1/11 -> Current date
 * 2011 -> Current Year
 */

/* Set package info be sure to set all of these */
define('PKG_NAME','GetMyComponent');
define('PKG_NAME_LOWER','getmycomponent');
define('PKG_VERSION','1.0.0');
define('PKG_RELEASE','beta1');



/******************************************
 * Work begins here
 * ****************************************/

/* set start time */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define sources */
$root = dirname(dirname(__FILE__)) . '/';

$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'docs' => $root . 'docs/',
);
unset($root);
$sourcePath = dirname(dirname(dirname(__FILE__))) . '/mycomponent';
$targetPath = "return MODX_ASSETS_PATH . 'mycomponents/';";

/* Instantiate MODx -- if this require fails, check your
 * _build/build.config.php file
 */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');

$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
$modx->log(xPDO::LOG_LEVEL_INFO, "Initializing MODx."); flush();
$modx->log(xPDO::LOG_LEVEL_INFO, "Loading Package Builder."); flush();
/* load builder */
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$package = $builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
flush();

$modx->log(xPDO::LOG_LEVEL_INFO, "Packaging Files (this may take a while) . . ."); flush();

$obj = array ('source' => $sourcePath,'target' =>$targetPath);
$attributes = array('vehicle_class' => 'xPDOFileVehicle');
$package->put($obj,$attributes);

$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),

));
$modx->log(xPDO::LOG_LEVEL_INFO, "Zipping up Package. Please wait . . ."); flush();

/* Last step - zip up the package */
$builder->pack();

/* report how long it took */
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(xPDO::LOG_LEVEL_INFO, "Package Built.");
$modx->log(xPDO::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();
