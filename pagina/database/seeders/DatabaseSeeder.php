<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Orden de ejecución respetando dependencias:
        
        // 1. Comunidades (base para todo)
        $this->call(ComunidadSeeder::class);
        
        // 2. Super administradores (tabla independiente)
        $this->call(SuperAdminSeeder::class);
        
        // 3. Administradores de comunidad (depende de comunidades)
        $this->call(AdminComunidadSeeder::class);
        
        // 4. Personal de seguridad (depende de comunidades)
        $this->call(PersonalSeguridadSeeder::class);
        
        // 5. Instalaciones (depende de comunidades)
        $this->call(InstalacionSeeder::class);
        
        // 5. Mapa (depende de comunidades)
        $this->call(MapaSeeder::class);
        
        // 6. Servicios (depende de comunidades)
        $this->call(ServicioSeeder::class);
        
        // 7. Residentes (depende de comunidades)
        $this->call(ResidenteSeeder::class);
        
        // 8. Novedades (depende de comunidades y administradores)
        $this->call(NovedadSeeder::class);
        
        // 9. Alertas de seguridad (depende de comunidades y administradores)
        $this->call(AlertaSeguridadSeeder::class);
        
        // 10. Eventos (depende de comunidades y administradores)
        $this->call(EventoSeeder::class);
        
        // 11. Reglamentos (depende de comunidades y administradores)
        $this->call(ReglamentoSeeder::class);

        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
    }
}
