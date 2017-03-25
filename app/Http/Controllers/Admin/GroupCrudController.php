<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupRequest as StoreRequest;
use App\Http\Requests\GroupRequest as UpdateRequest;
use App\Models\Group;
use Backpack\PermissionManager\app\Models\Permission;
use Spatie\Permission\Models\Role;

class GroupCrudController extends AppCrudController
{


    public function setUp()
    {
        parent::setup();

        /*
                 |--------------------------------------------------------------------------
                 | BASIC CRUD INFORMATION
                 |--------------------------------------------------------------------------
                 */
        $this->crud->setModel(Group::class);
        $this->crud->setRoute('admin/group');
        $this->crud->setEntityNameStrings('group', 'groups');
        $this->crud->enableDetailsRow();
        $this->crud->enableExportButtons();


        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/


        // $this->crud->setFromDb();
        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => '#', // Table column heading
            'type' => 'number'
        ]);

        $this->crud->addColumn('name');

        $this->crud->addField([
            'name' => 'name',
            'tab' => 'General'
        ]);

        $this->crud->addField([
            'name' => 'slug',
            'label' => 'Slug (URL)',
            'type' => 'text',
            'hint' => 'Will be automatically generated from your title, if left empty.',
            'tab' => 'General'
            // 'disabled' => 'disabled'
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Users",
            'type' => 'select2_multiple',
            'name' => 'users', // the method that defines the relationship in your Model
            'entity' => 'users', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => User::class, // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?,
            'tab' => 'Users'
        ]);

        $this->crud->denyAccess(['list', 'create', 'update', 'delete']);

        if ($this->user->can('list group')) {
            $this->crud->allowAccess('list');
        }

        if ($this->user->can('create group')) {
            $this->crud->allowAccess('create');
        }

        if ($this->user->can('update group')) {
            $this->crud->allowAccess('update');
        }

        if ($this->user->can('delete group')) {
            $this->crud->allowAccess('delete');
        }

    }

    public function showDetailsRow($id)
    {
        return view('admin.group.group_detail_row', ['users' => $this->crud->getEntry($id)->users]);
    }


    public function store(StoreRequest $request)
    {
        $item = parent::storeCrud();
        $name = $request->get('name');

        $admin = Role::findByName('admin');

        return $item;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
