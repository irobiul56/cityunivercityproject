<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FronendController extends Controller
{
    public function index() {

        return Inertia::render('Dashboard');
    }

    public function about(){
        return Inertia::render('Frontend/About');
    }

    public function requisition( Request $request) {

        $user = $request->user();

        if ($user->role->name === 'Administrator') {
            // Admin sees all applications
            $requisitions = Requisition::with('products', 'users') ->get();
        } else {
            // Regular user sees only their applications
            $requisitions = Requisition::with('products', 'users') -> where('requested_by', $user->id)->get();
        }
        return Inertia::render('Inventory/RequisitionList',[
            'requisition'   => $requisitions,
        ]);
    }
}
