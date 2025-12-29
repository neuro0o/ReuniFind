<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FAQTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $faqs = [
            // General Questions
            [
                'faqQuestion' => 'What is ReuniFind?',
                'faqAnswer' => 'ReuniFind is a web-based Lost & Found System designed specifically for Universiti Malaysia Sabah (UMS) students and staff. It helps streamlining the process of reporting, searching, and recovering lost or found items within the university campus through features like item reporting with image uploads, interactive campus map tracking, auto item matching, and handover chat room for easier communication between item owner and finder.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'Who can use ReuniFind?',
                'faqAnswer' => 'ReuniFind is available to all UMS community members including students, faculty members, and staff.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'Is ReuniFind free to use?',
                'faqAnswer' => 'Yes, ReuniFind is completely FREE to use for all UMS community members. There are no hidden fees or charges for reporting items or using any of the platform features.',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Lost & Found Reporting
            [
                'faqQuestion' => 'How do I report a lost item?',
                'faqAnswer' => "To report a lost item:\n1. Log in to your ReuniFind account\n2. Click on \"Report Lost Item\"\n3. Fill in the item details including name, category, description, and last seen location\n4. Upload a photo of the item (optional but recommended)\n5. Add verification details to proof ownership\n6. Submit your report. Your report will be reviewed by an admin before being published",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'How do I report a found item?',
                'faqAnswer' => "To report a found item:\n1. Log in to your ReuniFind account\n2. Click on \"Report Found Item\"\n3. Provide details about the item including category, description, and where you found it\n4. Upload a photo of the item\n5. Add verification information to proof you found the item\n6. Submit your report. Your report will be reviewed by an admin before being published.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'What information should I include when reporting an item?',
                'faqAnswer' => 'Include as much details as possible especially in the item description by including color, brand, model, and distinctive features. Ensure you upload clear photos as well. The more information you provide, the higher the chance of successful recovery.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'Can I edit my report after submitting it?',
                'faqAnswer' => 'Yes, you can edit your report at any time before it is marked as completed. Go to "My Reports", find your report, and click "Edit" to update any information.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'Why do I need to provide verification information?',
                'faqAnswer' => 'Verification details help prove that you are the genuine owner or finder of an item. This security measures prevents fraudulent claims and ensures items are returned to their rightful owners. Verification details can include purchase receipts, unique identifying marks, or photos showing you with the item.',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Item Matching & Search
            [
                'faqQuestion' => 'How does the item matching feature work?',
                'faqAnswer' => 'ReuniFind uses matching algorithm that compares your lost item report with all found item reports (and vice versa). The system analyzes item descriptions, categories, locations, and dates, to suggest potential matches. You will be notified when similar items are found.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'What are Suggested Matches?',
                'faqAnswer' => 'Suggested Matches are items that our system identifies as potentially matching your lost or found reports based on similarities in description, category, location, and timing. You can review these suggestions and contact the other party if you believe it is a right match.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'How do I search for my lost item?',
                'faqAnswer' => 'Use the "View All Reports" page to browse all published found items. You can filter by item type (Lost/Found), category, and use keywords to search for specific items. The search function checks both item names and descriptions for matches.',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Handover & Communication
            [
                'faqQuestion' => 'How do I claim a found item that matches mine?',
                'faqAnswer' => "When you find a potential match:\n1. Click \"View More Details\" to see full information\n2. If it matches your item, click \"Claim It!\" button\n3. Submit a handover request with additional verification details\n4. Wait for the finder to accept your request\n5. Use the secure chat to arrange pickup details",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'How does the handover process work?',
                'faqAnswer' => "Once a handover request is accepted:\n1. Both parties can communicate through the secure private chat\n2. Arrange a safe meeting location and time on campus\n3. Generate HandoverForm to be signed during exchange (Found on top right of the chat window)\n4. Complete the item exchange\n5. Upload proof of handover (Signed Handover Form)\n6. The system will keep the HandoverForm as official documentation",
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => 'Is the chat feature secure and private?',
                'faqAnswer' => 'Yes, all communications through ReuniFind chat are private and secure. Only you and the other party involved in the handover process can see the message.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'faqQuestion' => "What if someone claims my found item, but I'm not sure they're the real owner?",
                'faqAnswer' => "You have full control over handover requests. Review the verification details provided in the claim request carefully. You can ask additional questions through chat before accepting. If you're not convinced, you can reject the request with a reason. Remember to always meet in public, well-lit campus areas.",
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('faqs')->insert($faqs);

        $this->command->info('FAQ seeder completed successfully! ' . count($faqs) . ' FAQs inserted.');
    }
}