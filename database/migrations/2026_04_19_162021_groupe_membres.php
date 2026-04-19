<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('groupe_membres', function (Blueprint $collection) {
            $collection->index(['groupe_id', 'utilisateur_id'], null, null, ['unique' => true]);
        });
    }

    public function down()
    {
        Schema::drop('groupe_membres');
    }
};