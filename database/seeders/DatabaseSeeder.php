<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users
        $users = User::factory(10)->create();

        // Each user has 3–5 posts
        $users->each(function ($user) {
            $posts = Post::factory(rand(3, 5))->create(['user_id' => $user->id]);

            // Each post gets 2–4 comments
            $posts->each(function ($post) {
                Comment::factory(rand(2, 4))->create([
                    'post_id' => $post->id,
                    'user_id' => $post->user_id, // or random user
                ]);
            });

            // Each user has 1–3 orders
            Order::factory(rand(1, 3))->create(['user_id' => $user->id]);
        });
    }
}
