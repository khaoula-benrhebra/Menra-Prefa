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
            'manage_users' => 'Gérer les utilisateurs internes de Menara',
            
        ];
        
        foreach ($permissions as $id => $name) {
            Permission::create(['id' => $id, 'name' => $name]);
            $permissionIds[$id] = $id;
        }
        
      
        $adminRole = Role::where('name', 'Admin')->first();
        $responProdRole = Role::where('name', 'Responsable production')->first();
        $agentComerRole = Role::where('name', 'Agent commercial')->first();
        $clientRole = Role::where('name', 'Client')->first();
        
    
        $adminPermissions = [
            $permissionIds['manage_users'],
            
            
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