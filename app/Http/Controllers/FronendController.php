<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FronendController extends Controller
{
    public function index() {

        return Inertia::render('Dashboard');
    }

    public function about(){
        return Inertia::render('Frontend/About');
    }

    public function requisition() {
        $requisitions = Requisition::with('products')->get();
        return Inertia::render('Inventory/RequisitionList',[
            'requisition'   => $requisitions,
        ]);
    }
}
