<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    /**
     * Show the pending approval page.
     */
    public function index()
    {
        return view('pending-approval');
    }
}