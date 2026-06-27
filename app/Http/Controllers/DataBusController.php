<?php

namespace App\Http\Controllers;

class DataBusController extends Controller {
    public function index() {
        return view('data-bus/index');
    }

    public function create() {
        return view('data-bus/create');
    }
}