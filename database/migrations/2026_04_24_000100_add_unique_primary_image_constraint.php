<?php

use Illuminate\Database\Migrations\Migration;

// MySQL does not support partial/conditional unique indexes.
// Primary image uniqueness is enforced at the application level in ProductImage model boot().
return new class extends Migration
{
    public function up(): void {}

    public function down(): void {}
};
