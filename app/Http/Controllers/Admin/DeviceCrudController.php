<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeviceRequest;
use App\Models\Action;
use App\Models\Device;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class DeviceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeviceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as traitStore;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Device::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/device');
        CRUD::setEntityNameStrings('device', 'devices');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'label' => 'Label',
            'name' => 'label',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'text'
        ]);
    }

    protected function setupShowOperation()
    {
        CRUD::set('show.setFromDb', false);

        CRUD::addColumn([
            'label' => 'Label',
            'name' => 'label',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Actions',
            'name' => 'actions',
            'type' => 'actions'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DeviceRequest::class);

        CRUD::addField([
            'label' => 'Label',
            'name' => 'label',
            'type' => 'text'
        ]);

        CRUD::addField([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea'
        ]);

        CRUD::addField([   // repeatable
            'name' => 'actions',
            'label' => 'Action',
            'type' => 'repeatable',
            'fields' => [
                [
                    'label' => 'Label',
                    'name' => 'label',
                    'type' => 'text',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],

                [
                    'label' => 'Commande',
                    'name' => 'cmd',
                    'type' => 'text',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'wrapper' => ['class' => 'form-group col-md-12'],
                ],
            ],

            // optional
            'new_item_label' => 'Ajouter une action', // customize the text of the button
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    // redefine
    public function store(Request $request)
    {
        $request = $this->crud->validateRequest();

        $datas = $request->all();

        // create && save device
        $device = new Device();
        $device->label = $datas['label'];
        $device->description = $datas['description'];
        $device->save();

        // create && save each actions for created device
        $action_datas = json_decode($datas['actions'], true);
        foreach( $action_datas as $action_data )
        {
            $action = new Action();
            $action->device_id = $device->id;
            $action->label = $action_data['label'];
            $action->cmd = $action_data['cmd'];
            $action->description = $action_data['description'];
            $action->save();
        }

        $is_ok = true;

        // notify
        if( $is_ok )
        {
            $type = "success";
            $msg = "Le device vient d'être correctement créer.";
        }
        else
        {
            $type = 'error';
            $msg = "Echec de l'ajout du device.";
        }

        \Alert::$type($msg)->flash();

        // end
        return $this->crud->performSaveAction($device->id);
    }

    public function update(Request $request)
    {
        $request = $this->crud->validateRequest();

        $datas = $request->all();

        // update found device
        $device = Device::find($datas['id']);
        $device->label = $datas['label'];
        $device->description = $datas['description'];
        $device->save();

        // remove all old actions
        Action::where('device_id', $device->id)->delete();

        // create && save each actions for created device
        $action_datas = json_decode($datas['actions'], true);
        foreach( $action_datas as $action_data )
        {
            $action = new Action();
            $action->device_id = $device->id;
            $action->label = $action_data['label'];
            $action->cmd = $action_data['cmd'];
            $action->description = $action_data['description'];
            $action->save();
        }

        $is_ok = true;

        // notify
        if( $is_ok )
        {
            $type = "success";
            $msg = "Le device vient d'être correctement mis à jour.";
        }
        else
        {
            $type = 'error';
            $msg = "Echec de la mise à jour du device.";
        }

        \Alert::$type($msg)->flash();

        // end
        return $this->crud->performSaveAction($device->id);
    }

    // private
}
