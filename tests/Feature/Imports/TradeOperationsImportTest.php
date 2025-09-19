<?php

namespace Tests\Feature\Imports;

use App\Imports\TradeOperationsImport;
use App\Models\TradeOperation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TradeOperationsImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_trade_csv_creates_records(): void
    {
        $clientRole = Role::create(['name' => 'client', 'guard_name' => 'web']);
        $user = User::factory()->create(['name' => 'Pharmacie Centrale Tiznit']);
        $user->assignRole($clientRole);

        Storage::fake('local');
        $path = 'imports/trade_import.csv';
        $csv = implode("\n", [
            'labo,produit,date_challenge_debut,date_challenge_fin,compensation,type,envoye_le,recu,via',
            'Sanofi,Doliprane,2025-09-01,2025-09-15,500,amount,2025-08-28,oui,Email',
        ]);
        Storage::disk('local')->put($path, $csv);

        $full = Storage::disk('local')->path($path);
        Excel::import(new TradeOperationsImport($user->id), $full);

        $this->assertDatabaseCount('trade_operations', 1);
        $op = TradeOperation::first();
        $this->assertNotNull($op);
        $this->assertSame($user->id, $op->user_id);
        $this->assertEquals('2025-09-01', optional($op->challenge_start)->format('Y-m-d'));
        $this->assertEquals('2025-09-15', optional($op->challenge_end)->format('Y-m-d'));
        $this->assertEquals(500.0, (float) $op->compensation);
        $this->assertEquals('amount', $op->compensation_type);
        $this->assertEquals('2025-08-28', optional($op->sent_at)->format('Y-m-d'));
        $this->assertTrue((bool) $op->received);
        $this->assertEquals('Email', $op->via);
        $this->assertEquals('Sanofi', optional($op->lab)->name);
        $this->assertEquals('Doliprane', optional($op->product)->name);
    }
}
