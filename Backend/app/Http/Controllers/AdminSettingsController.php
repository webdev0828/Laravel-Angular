<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index() {
        $title = 'Email Notification';
        $emailSend = \App\AdminSetting::find(1)->email_send;

        return view('admin.admin_settings.index', compact('title', 'emailSend'));
    }

    public function changeStatus(Request $request) {
        $adminSetting = \App\AdminSetting::find(1);
        $adminSetting->email_send = $request->get('email_send');
        $adminSetting->save();

        return true;
    }
}