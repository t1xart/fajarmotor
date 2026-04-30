<?php

use Illuminate\Database\Migrations\Migration;

// Soft deletes columns already added by 2026_04_23_194827_add_soft_deletes_to_tables.php
// This file is intentionally a no-op to avoid duplicate column errors.
return new class extends Migration
{
    public function up(): void {}

    public function down(): void {}
};
