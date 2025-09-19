<?php
// database/seeders/PostSeeder.php
namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Articles publiés, programmés et brouillons
        Post::factory()->count(3)->published()->create();
        Post::factory()->count(1)->scheduled()->create();
        Post::factory()->count(1)->create(); // statut aléatoire
    }
}
