<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(database_path('seeders/Data.csv'), 'r');
        $csv->setHeaderOffset(0); // Assuming the first row contains headers

        $records = $csv->getRecords();

        foreach ($records as $record) {
            DB::table('data')->insert([
                'end_year' => $record['end_year'],
                'citylng' => $record['citylng'],
                'citylat' => $record['citylat'],
                'intensity' => $record['intensity'],
                'sector' => $record['sector'],
                'topic' => $record['topic'],
                'insight' => $record['insight'],
                'swot' => $record['swot'],
                'url' => $record['url'],
                'region' => $record['region'],
                'start_year' => $record['start_year'],
                'impact' => $record['impact'],
                'added' => $record['added'],
                'published' => $record['published'],
                'city' => $record['city'],
                'country' => $record['country'],
                'relevance' => $record['relevance'],
                'pestle' => $record['pestle'],
                'source' => $record['source'],
                'title' => $record['title'],
                'likelihood' => $record['likelihood'],
            ]);
        }
    }
}
