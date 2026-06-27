<?php

namespace App\Http\Controllers;

class PaketServiceController extends Controller {
    public function index() {
        return view('paket-service/index');
    }

    public function create() {
        return view('paket-service/create');
    }
}