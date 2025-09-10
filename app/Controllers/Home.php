<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }
        public function mocktest($id = null)
    {
        return view('exam', ['id' => $id]);
    }
        public function course($name = null)
    {
        $data = ['name' => $name];

        return view('comman/header', $data)   // load header
            . view('course', $data) 
            . view('comman/footer',$data);          // load course page
                    // load footer (optional)
    }
}
