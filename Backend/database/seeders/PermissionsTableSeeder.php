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
            'create_rawMaterial'=>'créer une matière première',   
            'update_rawMaterial'=>'modifier une matière première',   
            'delete_rawMaterial'=>'Supprimer une matière première',   
            'create_order'=>'créer commande',
            'Cancel_order'=>'annuler commande',
            'update_order'=>'modifier commande',
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
            $permissionIds['create_rawMaterial'],
            $permissionIds['update_rawMaterial'],
            $permissionIds['delete_rawMaterial'],
            
        ];
        $responProdRole->permissions()->attach($responProdPermissions);






        $agentComerPermissions = [
            
        ];
        $agentComerRole->permissions()->attach($agentComerPermissions);
        
       




        $clientPermissions = [
             $permissionIds['create_order'], 
             $permissionIds['Cancel_order'], 
             $permissionIds['update_order'], 
        ];
        $clientRole->permissions()->attach($clientPermissions);
    }
}