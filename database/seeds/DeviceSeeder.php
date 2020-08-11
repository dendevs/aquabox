<?php

use App\Models\Action;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->delete();
        DB::table('actions')->delete();

        $datas = [
            [
                'label' => 'Eclairage',
                'description' => "Gestion des durées d'éclairage",
                'actions' => [
                    [
                        'label' => 'Allumer',
                        'description' => 'Allumer les lampes',
                        'cmd' => 'aquabox.py -r 1 -s on',
                    ],
                    [
                        'label' => 'Eteindre',
                        'description' => 'Eteindre les lampes',
                        'cmd' => 'aquabox.py -r 1 -s off',
                    ],
                ]
            ],
            [
                'label' => 'Filtration',
                'description' => 'Gestion des durées de filtration',
                'actions' => [
                    [
                        'label' => 'Allumer',
                        'description' => 'Allumer les lampes',
                        'cmd' => 'aquabox.py -r 2 -s on',
                    ],
                    [
                        'label' => 'Eteindre',
                        'description' => 'Eteindre les lampes',
                        'cmd' => 'aquabox.py -r 2 -s off',
                    ],
                ]
            ],

        ];

        foreach( $datas as $data )
        {
            $device = new Device();
            $device->label = $data['label'];
            $device->description = $data['description'];
            $device->save();

            foreach( $data['actions'] as $action_data )
            {
                $action = new Action();
                $action->device_id = $device->id;
                $action->label = $action_data['label'];
                $action->description = $action_data['description'];
                $action->cmd = $action_data['cmd'];
                $action->save();
            }
        }
    }
}
