<?php

namespace Tests\Feature\Imports;

use App\Imports\PurchasesImport;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PurchasesImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_purchases_csv_creates_records(): void
    {
        $clientRole = Role::create(['name' => 'client', 'guard_name' => 'web']);
        $user = User::factory()->create(['name' => 'Pharmacie Centrale Tiznit']);
        $user->assignRole($clientRole);

        Storage::fake('local');
        $path = 'imports/purchases_import.csv';
        $csv = implode("\n", [
            'labo,type,nom_commerciale,derniere_commande,valeur,prochaine_commande,objectif_annuel,statut',
            'Sanofi,Médicaments,Mme Laila,2025-09-05,2500,2025-10-10,20000,livree',
        ]);
        Storage::disk('local')->put($path, $csv);

        $full = Storage::disk('local')->path($path);
        Excel::import(new PurchasesImport($user->id), $full);

        $this->assertDatabaseCount('purchases', 1);
        $purchase = Purchase::first();
        $this->assertNotNull($purchase);
        $this->assertSame($user->id, $purchase->user_id);
        $this->assertSame('Médicaments', $purchase->type);
        $this->assertEquals('2025-09-05', optional($purchase->last_order_date)->format('Y-m-d'));
        $this->assertEquals(2500.0, (float) $purchase->last_order_value);
        $this->assertEquals('2025-10-10', optional($purchase->next_order_date)->format('Y-m-d'));
        $this->assertEquals(20000.0, (float) $purchase->annual_target);
        $this->assertEquals('livree', $purchase->status);
        $this->assertEquals('Sanofi', optional($purchase->lab)->name);
        $this->assertEquals('Mme Laila', optional($purchase->commercial)->name);
    }
}
