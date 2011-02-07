<?php
/**
 * GetMyComponent pre-install script
 *
 * Copyright 2011 Your Name <you@yourdomain.com>
 * @author Your Name <you@yourdomain.com>
 * 1/1/11
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
 */
/**
 * Description: Attempts to create assets/components/GetMyComponents/GetMyComponent
 * directory.
 *
 * @package GetMyComponent
 * @subpackage build
 */
/**
 * @package GetMyComponent
 * Validators execute before the package is installed. If they return
 * false, the package install is aborted. This example attempts to
 * create the assets/components/mycomponents/mycomponent directory
  * and aborts the install if it is not found.
 */

/* The $modx object is not available here. In its place we
 * use $object->xpdo
 */
$modx =& $object->xpdo;


$modx->log(xPDO::LOG_LEVEL_INFO,'Running PHP Validator.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:

        $modx->log(xPDO::LOG_LEVEL_INFO,'Attempting to create assets/components/mycomponents directory.'); flush();
        $success = true;
        $outfile = MODX_ASSETS_PATH . 'components/mycomponents/';
        if (file_exists($outfile) == false) {
            $makeDir = mkdir($outfile, 0700, true);
            if (! $makeDir) {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Failed to create directory ' . $outfile . '. . . Create it yourself and reinstall.<br />');flush();
                $success = false;
            }
        }
        break;
   /* These cases must return true or the upgrade/uninstall will be cancelled */
   case xPDOTransport::ACTION_UPGRADE:
        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

return $success;