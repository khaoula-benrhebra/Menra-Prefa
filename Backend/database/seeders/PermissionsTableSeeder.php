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
            'create_categories'=>'Créer des catégories ',
            'update_categories'=>'Modifer des catégories',
            'delete_categories'=>'Supprimer des catégories',
            'create_product'=>'créer un produit',
            'update_product'=>'Modidfier un produit',
            'delete_product'=>'Supprimer un produit',   
            
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
            $permissionIds['create_categories'],
            $permissionIds['update_categories'],
            $permissionIds['delete_categories'],
            $permissionIds['create_product'],
            $permissionIds['update_product'],
            $permissionIds['delete_product'],
            
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