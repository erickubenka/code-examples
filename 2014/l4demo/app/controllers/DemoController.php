<?php
/**
 * Created by PhpStorm.
 * User: eric.kubenka
 * Date: 25.02.14
 * Time: 13:59
 */


class DemoController extends BaseController{

    /**
     * @return mixedAccess via get and call index
     */
    public function getIndex()
    {
        return View::make('demo.home');
    }
} 