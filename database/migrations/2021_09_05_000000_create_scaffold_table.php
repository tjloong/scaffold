<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateScaffoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->makePasswordNullable();
        $this->createTeamsTables();
        $this->createRolesTables();
        $this->createAbilitiesTables();
    }

    /**
     * Make password nullable
     */
    public function makePasswordNullable($up = true)
    {
        if ($up) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password')->nullable()->change();
            });
        }
        else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password')->change();
            });    
        }
    }

    /**
     * Create roles table
     * 
     * @return void
     */
    public function createRolesTables($up = true)
    {
        if ($up) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('access')->nullable();
                $table->boolean('is_system')->nullable();
                $table->timestamps();
            });
    
            Schema::table('users', function(Blueprint $table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('remember_token');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
    
            DB::table('roles')->insert([
                [
                    'name' => 'Administrator', 
                    'access' => 'global',
                    'is_system' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Restricted User', 
                    'access' => 'restrict',
                    'is_system' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
        else {
            Schema::table('users', function(Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
    
            Schema::dropIfExists('roles');    
        }
    }

    /**
     * Create abilities tables
     * 
     * @return void
     */
    public function createAbilitiesTables($up = true)
    {
        if ($up) {
            Schema::create('abilities', function (Blueprint $table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('name')->nullable();
                $table->timestamps();
            });
    
            Schema::create('abilities_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ability_id')->nullable();
                $table->unsignedBigInteger('role_id')->nullable();
    
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
    
            Schema::create('abilities_users', function (Blueprint $table) {
                $table->id();
                $table->string('access')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('ability_id')->nullable();
    
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
            });

            $abilities = [
                ['module' => 'settings-user', 'name' => 'manage'],
                ['module' => 'settings-role', 'name' => 'manage'],
                ['module' => 'settings-team', 'name' => 'manage'],
            ];

            $restrictedUser = DB::table('roles')->where('name', 'Restricted User')->first();

            foreach ($abilities as $ability) {
                $id = DB::table('abilities')->insertGetId(
                    array_merge($ability, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()])
                );

                DB::table('abilities_roles')->insert(['role_id' => $restrictedUser->id, 'ability_id' => $id]);
            }
        }
        else {
            Schema::dropIfExists('abilities_users');
            Schema::dropIfExists('abilities_roles');
            Schema::dropIfExists('abilities');
        }
    }

    /**
     * Create teams tables
     * 
     * @return void
     */
    public function createTeamsTables($up = true)
    {
        if ($up) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
    
            Schema::create('teams_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
    
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });    
        }
        else {
            Schema::dropIfExists('teams_users');
            Schema::dropIfExists('teams');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->createTeamsTables(false);
        $this->createAbilitiesTables(false);
        $this->createRolesTables(false);
        $this->makePasswordNullable(false);
    }
}