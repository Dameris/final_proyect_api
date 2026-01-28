<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                "question" => "What is SkyUrban?",
                "answer" => "SkyUrban is a fashion brand that blends urban style with comfort, offering high-quality apparel for everyday wear.",
            ],
            [
                "question" => "What payment methods do you accept?",
                "answer" => "We accept Visa, Mastercard, PayPal, Apple Pay, and Google Pay.",
            ],
            [
                "question" => "How long does shipping take?",
                "answer" => "Shipping usually takes 3-7 business days, depending on your location.",
            ],
            [
                "question" => "Can I return an item?",
                "answer" => "Yes, we accept returns within 30 days of purchase as long as the item is unworn and in its original packaging.",
            ],
            [
                "question" => "How do I track my order?",
                "answer" => "Once your order is shipped, you’ll receive an email with a tracking number."
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
