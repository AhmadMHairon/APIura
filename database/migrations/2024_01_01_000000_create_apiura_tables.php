<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Saved API Requests table
        Schema::create('saved_api_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->string('name')->nullable();
            $table->string('priority')->nullable();
            $table->string('team', 50)->nullable();
            $table->string('method');
            $table->string('path');
            $table->json('path_params')->nullable();
            $table->json('query_params')->nullable();
            $table->json('headers')->nullable();
            $table->json('body')->nullable();
            $table->integer('response_status')->nullable();
            $table->json('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['method', 'path']);
            $table->index('module_id');
            $table->index('created_at');
        });

        // Saved API Request Comments table
        Schema::create('saved_api_request_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saved_api_request_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('author_name')->nullable();
            $table->enum('author_type', ['backend', 'frontend', 'qa', 'other'])->default('other');
            $table->text('content');
            $table->string('status')->nullable()->default('info');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('created_at');
        });

        // Saved API Flows table
        Schema::create('saved_api_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('steps');
            $table->json('default_headers')->nullable();
            $table->boolean('continue_on_error')->default(false);
            $table->timestamps();

            $table->index('module_id');
            $table->index('created_at');
        });

        // Apiura Modules table
        Schema::create('apiura_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('apiura_modules')->onDelete('cascade');
            $table->index('parent_id');
            $table->index('sort_order');
        });

        // Add foreign keys for module_id after apiura_modules table exists
        Schema::table('saved_api_requests', function (Blueprint $table) {
            $table->foreign('module_id')->references('id')->on('apiura_modules')->onDelete('set null');
        });

        Schema::table('saved_api_flows', function (Blueprint $table) {
            $table->foreign('module_id')->references('id')->on('apiura_modules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_api_request_comments');
        Schema::dropIfExists('saved_api_requests');
        Schema::dropIfExists('saved_api_flows');
        Schema::dropIfExists('apiura_modules');
    }
};
