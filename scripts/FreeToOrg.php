<?php

class Script_FreeToOrg extends Uop_Script_Abstract {
    public function run(){
        
        // Get de toutes les lignes avec un org_id NULL de la table free_imports
        $t_imports = App::table('FreeImports');
        $r_imports = $t_imports->select()->where('org_id IS NULL')->fetchAll();
        
        // Table User
        $t_users = App::table('Users');
        
        $t_locs = App::table("locations");
        
        // Get the user tattoome@tattoome.com
        $r_user = $t_users->findByEmail('tattoome@tattoome.com')->current();
        
        
        $this->info("Importing ".$r_imports->count()." lines");

        // Boucle pour chaque ligne de la table
        foreach ($r_imports as $r_import) {
        				$this->info("Importing #$r_import->id");

        
                // Recup de la location de la ville 
                $r_city = $t_locs->find($r_import->id_city)->current();
                if(!isset($r_city)){
	              	$this->info("City with id #$r_import->id_city not found");
                }
                
                
                // Nouvelle Organisation
                $r_org = $r_user->createOrganization();
                
                // Recup de la location de l'organization
                $r_loc = $r_org->location();
                
                
                // Ajout de la city à location de l'organization
                $r_loc->city($r_city);
                $r_loc->save();
                
                // Données à ajouter dans l'organization
                $dataOrg = array(
                    'address' => $r_import->address,
                    'status' => 'free'
                );
                
                // Ajout des données dans l'organization
                $r_org->setFromArray($dataOrg);
                
                $r_org->name($r_import->name);
                $r_org->shortDesc($r_import->short_description);
                $r_org->description($r_import->long_description);

                
                // Sauvegarde de l'organization
                $r_org->save();
                
                // Get de l'id de la nouvelle org précédemment ajoutée
                $org_id = $r_org->id;
                
                // Update du champ org_id de la ligne free_import avec la nouvelle org précédemment ajoutée
                $r_import->org_id = $org_id;
                
                // Sauvegarde de l'id organization dans la ligne du free_imports
                $r_import->save();
                
                // Publication de l'organization
	              $this->info("Publishing org #$r_org->id");
                $r_org->publish();
                
        }
    }
}

?>