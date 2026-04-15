<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // Carrega os logs com o utilizador relacionado, ordenados pelos mais recentes
        $logs = Log::with('user')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.logs.index', compact('logs'));
    }
}