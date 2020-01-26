<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('input_type')
                ->nullable()
                ->default('text')
                ->after('description')
                ->comment('text,textarea,radio,checkbox,dropdown,dropdown_multiple,file,image,pdf,audio,video');
            $table->text('input_options')
                ->nullable()
                ->after('input_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['input_type', 'input_options']);
        });
    }
}
