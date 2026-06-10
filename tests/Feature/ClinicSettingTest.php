<?php

use App\Models\ClinicSetting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Ensure roles are created
    $this->adminRole = Role::firstOrCreate(['nom' => 'ADMIN'], ['description' => 'Administrateur']);
    $this->userRole = Role::firstOrCreate(['nom' => 'CAISSIER'], ['description' => 'Caissier']);
});

test('guest cannot access settings page', function () {
    $response = $this->get('/settings');
    $response->assertRedirect('/login');
});

test('non-admin user cannot access settings page', function () {
    $user = User::factory()->create(['role_id' => $this->userRole->id]);

    $response = $this
        ->actingAs($user)
        ->get('/settings');

    $response->assertForbidden();
});

test('admin can access settings page', function () {
    $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

    $response = $this
        ->actingAs($admin)
        ->get('/settings');

    $response->assertOk();
    $response->assertSee('Paramètres de la clinique');
});

test('admin can update settings', function () {
    $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

    $response = $this
        ->actingAs($admin)
        ->put('/settings', [
            'nom_clinique' => 'Clinique de Test',
            'slogan' => 'Le meilleur slogan',
            'adresse' => '123 Rue de la Sante',
            'ville' => 'Yaounde',
            'telephone' => '+237 612 345 678',
            'email' => 'contact@testclinic.com',
            'site_web' => 'https://testclinic.com',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $settings = ClinicSetting::getInstance();
    expect($settings->nom_clinique)->toBe('Clinique de Test')
        ->and($settings->slogan)->toBe('Le meilleur slogan')
        ->and($settings->adresse)->toBe('123 Rue de la Sante')
        ->and($settings->ville)->toBe('Yaounde')
        ->and($settings->telephone)->toBe('+237 612 345 678')
        ->and($settings->email)->toBe('contact@testclinic.com')
        ->and($settings->site_web)->toBe('https://testclinic.com');
});

test('admin can upload a clinic logo', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
    $file = UploadedFile::fake()->image('logo.png');

    $response = $this
        ->actingAs($admin)
        ->put('/settings', [
            'nom_clinique' => 'Clinique de Test',
            'logo' => $file,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $settings = ClinicSetting::getInstance();
    expect($settings->logo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($settings->logo_path);
});
