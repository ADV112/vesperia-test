<?php

namespace Database\Seeders;

use App\Models\Input;
use App\Models\Option;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'username' => 'admin',
            'password' => Hash::make('admin')
        ]);

        // JSON Seeder
        $files = Storage::disk('public')->get('submission.json');
        $json = json_decode($files);

        try {
            DB::beginTransaction();
            foreach($json as $value) {
                // Inserting Sections
                Section::create([
                    'id' => $value->id,
                    'name' => $value->name
                ]);

                // Inserting Inputs
                $inputs = $value->payloads;
                foreach ($inputs as $input) {
                    $answerKey = null;

                    if(is_array($input->answer->value)) {
                        $answerKey = [];

                        foreach($input->answer->value as $answer) {
                            $answerKey[] = is_object($answer) ? $answer->id : $answer;
                        }

                        $answerKey = implode('|;|', $answerKey);
                    } else {
                        $answerKey = $input->answer->value;
                    }

                    Input::create([
                        'id' => $input->id,
                        'section_id' => $value->id,
                        'label' => $input->label,
                        'type' => $input->type,
                        'orm_only' => $input->orm_only,
                        'description' => $input->description,
                        'answer_key' => $answerKey
                    ]);

                    // Inserting Answer & Options
                    $options = $input->options;
                    foreach($options as $option) {
                        Option::create([
                            'id' => $option->id,
                            'input_id' => $input->id,
                            'label' => $option->label,
                            'value' => $option->value
                        ]);
                    }
                }

                DB::commit();
            }
        } catch(\Exception $e) {
            DB::rollBack();
            dump($e);
        }
    }
}
