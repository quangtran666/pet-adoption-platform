<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\Pet;
use App\Models\PetImage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        // Create admin regular
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        // Create more users
        $users = User::factory(8)->create();

        $activePets = Pet::factory(10)->active()->create([
            "user_id" => $users->random()->id,
        ]);

        $pendingPets = Pet::factory(5)->pendingApproval()->create([
            'user_id' => $users->random()->id,
        ]);

        $adoptedPets = Pet::factory(3)->adopted()->create([
            'user_id' => $users->random()->id,
        ]);

        // Create pet images (3 - 5 per pet)
        foreach ($activePets->merge($pendingPets)->merge($adoptedPets) as $pet) {
            PetImage::factory(random_int(3, 5))->create([
                'pet_id' => $pet->id,
            ]);
        }

        // Create adoption requests
        foreach ($activePets as $pet) {
            // Each active pet gets 0-3 pending requests
            $requestUsers = $users->except($pet->user_id)->random(random_int(0, 3));
            foreach ($requestUsers as $user) {
                AdoptionRequest::factory()->pending()->create([
                    'pet_id' => $pet->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        foreach ($adoptedPets as $pet) {
            // Each adopted pet gets 1 accepted request and 0-2 rejected requests
            $acceptedUser = $users->except($pet->user_id)->random();
            AdoptionRequest::factory()->accepted()->create([
                'pet_id' => $pet->id,
                'user_id' => $acceptedUser->id,
            ]);

            $rejectedUsers = $users->except([$pet->user_id, $acceptedUser->id])->random(random_int(0, 2));
            foreach ($rejectedUsers as $user) {
                AdoptionRequest::factory()->rejected()->create([
                    'pet_id' => $pet->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        // Create favorites
        foreach ($users as $user) {
            $favoritePets = $activePets->merge($adoptedPets)->random(random_int(0, 5));
            foreach ($favoritePets as $pet) {
                Favorite::factory()->create([
                    'user_id' => $user->id,
                    'pet_id' => $pet->id,
                ]);
            }
        }

        // Create messages between users
        foreach ($users as $sender) {
            $receivers = $users->except($sender->id)->random(random_int(1, 3));
            foreach ($receivers as $receiver) {
                // Create a conversation with 1-5 messages
                $messageCount = random_int(1, 5);
                for ($i = 0; $i < $messageCount; $i++) {
                    // Alternate between sender and receiver
                    if ($i % 2 === 0) {
                        Message::factory()->create([
                            'sender_id' => $sender->id,
                            'receiver_id' => $receiver->id,
                        ]);
                    } else {
                        Message::factory()->create([
                            'sender_id' => $receiver->id,
                            'receiver_id' => $sender->id,
                        ]);
                    }
                }
            }
        }
    }
}
