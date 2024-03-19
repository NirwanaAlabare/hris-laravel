<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;

class AdminUpdateVersionController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
        parent::__construct();
        $this->pageTitle = 'Update Log';
        $this->settingOpen = 'active open';
        $this->updateSettingActive = 'active';
    }

    public function index()
    {

        return view('admin.update-version.index', $this->data);
    }
}
