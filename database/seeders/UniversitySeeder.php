<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UniversitySeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run() {
         DB::table('uni')->insert([
            [
                'uniID' => 1,
                'uniName' => 'Tunku Abdul Rahman University of Management and Technology',
                'Address' => 'Jalan Genting Kelang, Setapak, 53300 Kuala Lumpur, Malaysia',
                'ContactNumber' => '+60 3-4145 0123',
                'OperationHour' => '08:00 AM - 05:00 PM',
                'DateOfOpenSchool' => '1969',
                'Category' => 'Private',
                'Description' => 'TARUMT is one of Malaysia’s top private institutions known for its academic excellence.',
                'Founder' => 'MCA',
                'EstablishDate' => '1969',
                'Ranking' => 5,
                'NumOfCourses' => 200,
                'image' => 'tarumt.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uniID' => 2,
                'uniName' => 'Universiti Malaya',
                'Address' => 'Jalan Universiti, 50603 Kuala Lumpur, Malaysia',
                'ContactNumber' => '+60 3-7967 7022',
                'OperationHour' => '08:30 AM - 05:30 PM',
                'DateOfOpenSchool' => '1949',
                'Category' => 'Public',
                'Description' => 'UM is Malaysia’s oldest and most prestigious university, offering diverse programs.',
                'Founder' => 'Government of Malaysia',
                'EstablishDate' => '1949',
                'Ranking' => 1,
                'NumOfCourses' => 300,
                'image' => 'um.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uniID' => 3,
                'uniName' => 'Universiti Teknologi Malaysia',
                'Address' => '81310 Skudai, Johor, Malaysia',
                'ContactNumber' => '+60 7-553 3333',
                'OperationHour' => '08:00 AM - 06:00 PM',
                'DateOfOpenSchool' => '1972',
                'Category' => 'Public',
                'Description' => 'UTM is a leading university in engineering, science, and technology education.',
                'Founder' => 'Government of Malaysia',
                'EstablishDate' => '1972',
                'Ranking' => 3,
                'NumOfCourses' => 250,
                'image' => 'utm.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uniID' => 4,
                'uniName' => 'Taylor\'s University',
                'Address' => 'Subang Jaya, Selangor, Malaysia',
                'ContactNumber' => '+60 3-5629 5000',
                'OperationHour' => '09:00 AM - 06:00 PM',
                'DateOfOpenSchool' => '1969',
                'Category' => 'Private',
                'Description' => 'Taylor\'s University is renowned for its high-quality education in business and hospitality.',
                'Founder' => 'Taylor’s Education Group',
                'EstablishDate' => '1969',
                'Ranking' => 4,
                'NumOfCourses' => 180,
                'image' => 'taylors.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uniID' => 5,
                'uniName' => 'Sunway University',
                'Address' => 'Bandar Sunway, 47500 Selangor, Malaysia',
                'ContactNumber' => '+60 3-7491 8622',
                'OperationHour' => '09:00 AM - 06:00 PM',
                'DateOfOpenSchool' => '2004',
                'Category' => 'Private',
                'Description' => 'Sunway University is a world-class private university focusing on academic excellence and research.',
                'Founder' => 'Sunway Education Group',
                'EstablishDate' => '2004',
                'Ranking' => 6,
                'NumOfCourses' => 150,
                'image' => 'sunway.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
