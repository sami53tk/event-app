<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un administrateur
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Créer des utilisateurs pour les tests
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'client',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'client',
        ]);

        User::factory()->create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'role' => 'organisateur',
        ]);
    }

    /** @test */
    public function admin_can_search_users_by_name()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('John Doe');

        // Vérifier que les utilisateurs sont filtrés correctement dans la collection
        $users = $response->viewData('users');
        $this->assertTrue($users->contains('name', 'John Doe'));
        $this->assertFalse($users->contains('name', 'Jane Smith'));
    }

    /** @test */
    public function admin_can_search_users_by_email()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'jane@example']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('Jane Smith');
        $response->assertDontSee('John Doe');
        $response->assertDontSee('Bob Johnson');
    }

    /** @test */
    public function admin_can_filter_users_by_role()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'organisateur']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('Bob Johnson');
        $response->assertDontSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    /** @test */
    public function admin_can_combine_search_and_role_filter()
    {
        // Créer un autre organisateur avec un nom similaire
        $johnOrganizer = User::factory()->create([
            'name' => 'John Organizer',
            'email' => 'john.organizer@example.com',
            'role' => 'organisateur',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', [
                'search' => 'John',
                'role' => 'organisateur',
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('John Organizer');

        // Vérifier que les utilisateurs sont filtrés correctement dans la collection
        $users = $response->viewData('users');
        $this->assertTrue($users->contains('id', $johnOrganizer->id));
        $this->assertFalse($users->contains('name', 'John Doe')); // Client, pas organisateur

        // Nous ne testons pas Bob Johnson ici car la recherche par nom peut inclure
        // des résultats partiels selon l'implémentation
    }

    /** @test */
    public function non_admin_users_cannot_access_user_search()
    {
        $client = User::factory()->create(['role' => 'client']);
        $organizer = User::factory()->create(['role' => 'organisateur']);

        // Test avec un client
        $responseClient = $this->actingAs($client)
            ->get(route('admin.users.index', ['search' => 'John']));
        $responseClient->assertStatus(403);

        // Test avec un organisateur
        $responseOrganizer = $this->actingAs($organizer)
            ->get(route('admin.users.index', ['search' => 'John']));
        $responseOrganizer->assertStatus(403);
    }
}
