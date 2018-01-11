<?php
namespace Database\Migrations;

use Database\Schema;

class SegundaMigracion extends Migrations
{
    public function up()
    {
        Schema::create('clients', function(Schema $table){
            $table->string('id', 16);
            $table->string('ip', 64);
            $table->int('status', 1);

            $table->primaryKey('id');
        });

        Schema::create('users', function(Schema $table){
            $table->increment('id');
            $table->string('client', 16);
            $table->string('ip', 64, [
                'comment' => 'ultimo ip conocido',
            ]);
            $table->string('nickname', 32);
            $table->string('email', 64);
            $table->string('password', 64);
            $table->string('avatar');

            $table->primaryKey('id');
            $table->unique('client');
            $table->unique('email');

        });

        Schema::create('messages', function(Schema $table){
            $table->increment('id');
            $table->int('from', 11, [
                'attribute' => 'unsigned',
                'comment' => 'user_id'
            ]);
            $table->int('to', 11, [
                'attribute' => 'unsigned',
                'comment' => 'user_id'
            ]);
            $table->string('data');
            $table->int('resource_type', 11, ['comment' => 'resource_id']);
            $table->string('resource', 256, ['default' => 'NULL']);
            $table->int('status', 2);
            $table->bool('tracked', ['default' => 'true']);
            $table->timestamps();

            $table->primaryKey('id');
        });

        Schema::create('resource_types', function(Schema $table){
            $table->increment('id');
            $table->string('name', 64);
            $table->int('opt', 2);

            $table->primaryKey('id');
        });

        Schema::create('status_types', function(Schema $table){
            $table->increment('id');
            $table->string('name', 64);
            $table->int('opt', 2);

            $table->primaryKey('id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('users');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('resource_types');
        Schema::dropIfExists('status_types');
    }
}
