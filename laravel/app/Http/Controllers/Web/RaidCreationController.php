<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;

class RaidCreationController extends Controller
{
    /**
     * Show the form for creating a new raid.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $members = Member::orderBy('mem_name', 'asc')->get();

        return view('dashboard.raids.create', compact('members'));
    }
}
