<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('messages_groupe', function (Blueprint $collection) {
            $collection->index('groupe_id');
            $collection->index('auteur_id');
        });
    }

    public function down()
    {
        Schema::drop('messages_groupe');
    }
};