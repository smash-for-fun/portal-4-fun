<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class AppCrudController extends CrudController
{
    protected $user;

  public function setup()
  {
      parent::setup();
      $this->user = Auth::user();

  }

}