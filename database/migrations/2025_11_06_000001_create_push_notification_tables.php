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
        // User notification tokens table
        Schema::create('user_notification_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('token'); // FCM token can be long
            $table->string('token_hash', 64)->unique(); // SHA256 hash for uniqueness
            $table->enum('device_type', ['web', 'android', 'ios'])->default('web');
            $table->string('browser', 100)->nullable();
            $table->string('device_name', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_active');
        });

        // Push notifications log table
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('website_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', [
                'donation', 
                'auction_outbid', 
                'auction_won', 
                'goal_reached', 
                'campaign_update',
                'investment_milestone',
                'ticket_purchased',
                'general'
            ])->default('general');
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable(); // Additional data (IDs, URLs, etc.)
            $table->enum('status', ['pending', 'sent', 'failed', 'read'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });

        // Notification preferences table
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('donations_enabled')->default(true);
            $table->boolean('auctions_enabled')->default(true);
            $table->boolean('goals_enabled')->default(true);
            $table->boolean('campaigns_enabled')->default(true);
            $table->boolean('investments_enabled')->default(true);
            $table->boolean('tickets_enabled')->default(true);
            $table->boolean('general_enabled')->default(true);
            $table->enum('frequency', ['realtime', 'hourly', 'daily'])->default('realtime');
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'website_id']);
        });

        // Notification statistics table
        Schema::create('notification_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('stat_date');
            $table->integer('sent_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->json('breakdown_by_type')->nullable(); // Count by notification type
            $table->timestamps();
            
            $table->unique(['user_id', 'stat_date']);
            $table->index('stat_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_statistics');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('push_notifications');
        Schema::dropIfExists('user_notification_tokens');
    }
};
