<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissionIds = [];
        $permissions = [
            'create_users' => 'créer les utilisateurs internes de Menara',
            
        ];
        
        foreach ($permissions as $id => $name) {
            Permission::create(['id' => $id, 'name' => $name]);
            $permissionIds[$id] = $id;
        }
        
      
        $adminRole = Role::where('nom', 'Admin')->first();
        $responProdRole = Role::where('nom', 'Responsable production')->first();
        $agentComerRole = Role::where('nom', 'Agent commercial')->first();
        $clientRole = Role::where('nom', 'Client')->first();
        
    
        $adminPermissions = [
            $permissionIds['create_users'],
            
            
        ];
        $adminRole->permissions()->attach($adminPermissions);
        
        




        $responProdPermissions = [
            
        ];
        $responProdRole->permissions()->attach($responProdPermissions);






        $agentComerPermissions = [
            
        ];
        $agentComerRole->permissions()->attach($agentComerPermissions);
        
       




        $clientPermissions = [
           
        ];
        $clientRole->permissions()->attach($clientPermissions);
    }
}