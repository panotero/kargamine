<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailerSetting;
use App\Models\User;
use App\Models\SettingRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function page_dashboard()
    {
        return view('pages.dashboard');
    }

    public function page_UserManagement()
    {

        $roles = SettingRole::all();
        $users = User::with('role')->get();

        return view('pages.usermanagement', compact('roles', 'users'));
    }
    public function page_Mailer()
    {

        $config = MailerSetting::latest()->first();
        return view('pages.settings.mailer', compact('config'));
    }
    public function page_Menus()
    {
        return view('pages.settings.menus');
    }

    public function page_Themes()
    {
        return view('pages.settings.theme');
    }
    public function page_Users()
    {
        return view('pages.settings.users');
    }
    public function page_Forms()
    {
        return view('pages.settings.forms');
    }

    public function page_featuredHome()
    {
        return view('pages.featuredHome');
    }

    public function page_settings()
    {
        return view('pages.settings.settings');
    }
    public function page_documents()
    {
        return view('pages.documents');
    }

    public function page_approvals()
    {

        return view('pages.approvals');
    }

    public function page_reports_documents()
    {

        return view('pages.reports.documents');
    }

    public function page_reports_users()
    {
        return view('pages.reports.users');
    }
    public function page_finance_tracker()
    {

        return view('pages.finance');
    }

    public function profile()
    {
        return "page profile";
    }

    public function settings()
    {
        return "page settings";
    }

    public function page_Maintenance()
    {
        return view('pages.maintenance');
    }

    public function page_bookings()
    {
        return view('pages.bookings');
    }
    public function page_shipperConsignee()
    {
        return view('pages.shipperConsignee');
    }
    public function page_contracts()
    {
        return view('pages.contracts');
    }
    public function page_reports()
    {
        return view('pages.reports');
    }
}
