<?php

namespace Softworx\RocXolid\DevKit\Http\Controllers;

use Auth;
use Softworx\RocXolid\Http\Controllers\AbstractController as RocXolidController;

// @todo "hotfixed"
abstract class AbstractController extends RocXolidController
{
    public function userCan($method_group)
    {
        $permission = sprintf('\%s.%s', get_class($this), $method_group);

        if ($user = Auth::guard('rocXolid')->user()) {
            if ($user->getKey() == 1) {
                return true;
            }
        }

        return false;
    }
}
