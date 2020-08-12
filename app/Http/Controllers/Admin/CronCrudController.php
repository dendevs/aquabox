<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CronRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CronCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CronCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Cron::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cron');
        CRUD::setEntityNameStrings('cron', 'crons');
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
            'name'         => 'action',
            'type'         => 'relationship',
            'label'        => 'Action',
            // OPTIONAL
            'entity'    => 'action',
            'attribute' => 'label_full',
            'model'     => App\Models\Action::class,
        ]);

        CRUD::addColumn([
            'label' => 'Heure',
            'name' => 'hour',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Minute',
            'name' => 'minute',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Jour',
            'name' => 'day',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Semaine',
            'name' => 'week',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'label' => 'Mois',
            'name' => 'month',
            'type' => 'text'
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
        CRUD::setValidation(CronRequest::class);

        CRUD::addField([
            'label'     => "Action",
            'type'      => 'select2',
            'name'      => 'action_id',
            'entity'    => 'action',
            'attribute' => 'label_full',
            'model'     => "App\Models\Action",
            'options'   => (function ($query) {
                return $query->orderBy('label', 'ASC')->get();
            }),
        ]);

        $hours = [ -1 => '*'];
        for( $i = 0; $i <= 24; $i++)
            $hours[$i] = $i . 'H';
        CRUD::addField([
            'name'        => 'hour',
            'label'       => "Heure",
            'type'        => 'select2_from_array',
            'options'     => $hours,
            'allows_null' => false,
        ]);

        for( $i = 0; $i <= 60; $i++)
            $minutes[$i] = $i . 'm';
        CRUD::addField([
            'name'        => 'minute',
            'label'       => "Minute",
            'type'        => 'select2_from_array',
            'options'     => $minutes,
            'allows_null' => false,
        ]);

        $days = [ -1 => '*', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mecredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche' ];
        CRUD::addField([
            'name'        => 'day',
            'label'       => "Jour",
            'type'        => 'select2_from_array',
            'options'     => $days,
            'allows_null' => false,
        ]);

        $weeks = [ -1 => '*', 1 => '1', 2 => '2', 3 => '3', 4 => '4' ];
        CRUD::addField([
            'name'        => 'week',
            'label'       => "Semaine",
            'type'        => 'select2_from_array',
            'options'     => $weeks,
            'allows_null' => false,
        ]);

        $months = [ -1 => '*', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre' ];
        CRUD::addField([
            'name'        => 'month',
            'label'       => "Mois",
            'type'        => 'select2_from_array',
            'options'     => $months,
            'allows_null' => false,
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
}
