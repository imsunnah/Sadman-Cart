<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // About Us Group
            [
                'title' => 'About Us',
                'group' => 'about_us',
                'description' => 'Learn more about Sadman Cart, our core mission, operational vision, and how we deliver top surgical products and smart electronics.',
                'content' => '<h1>About Sadman Cart</h1><p>Welcome to Sadman Cart, your ultimate destination for high-quality Surgical Products, Smart Electronics, and Premium Accessories. We are committed to bringing the best products directly to your doorstep with unmatched service.</p><p>Founded in 2026, we aim to provide an exceptional e-commerce experience with dynamic pricing, transparent discounts, and fast logistics across Bangladesh.</p>',
            ],
            [
                'title' => 'Contact Us',
                'group' => 'about_us',
                'description' => 'Get in touch with Sadman Cart customer care via phone, support email, or office address. We are here to support you 24/7.',
                'content' => '<h1>Contact Us</h1><p>We are here to assist you 24/7. Get in touch with us through any of the channels below:</p><ul><li><strong>Phone:</strong> +880 1712-345678</li><li><strong>Email:</strong> support@sadmancart.com</li><li><strong>Address:</strong> Road 12, Banani, Dhaka, Bangladesh</li></ul><p>Or send us a message on our Facebook page for instant support!</p>',
            ],
            [
                'title' => 'Terms & Conditions',
                'group' => 'about_us',
                'description' => 'Read the terms, conditions, and legal agreements required to browse, interact, and buy on the Sadman Cart portal.',
                'content' => '<h1>Terms & Conditions</h1><p>Please read these Terms & Conditions carefully before using our platform. By accessing or using Sadman Cart, you agree to be bound by these terms.</p><p>We reserve the right to update or modify these terms at any time without prior notice. Your continued use of the platform constitutes agreement to the updated terms.</p>',
            ],
            [
                'title' => 'Privacy Policy',
                'group' => 'about_us',
                'description' => 'Understand how we protect, store, and process your personal data, secure payment transcripts, and delivery addresses.',
                'content' => '<h1>Privacy Policy</h1><p>At Sadman Cart, we value your privacy. We collect, store, and process your personal data (name, email, phone number, shipping address) solely to fulfill your orders and enhance your user experience.</p><p>We will never sell or share your data with unauthorized third parties. Secure payment processing is handled through certified gateways.</p>',
            ],
            [
                'title' => 'Careers',
                'group' => 'about_us',
                'description' => 'Explore current openings, build the future of e-commerce, and join our creative product, engineering, and logistics teams.',
                'content' => '<h1>Careers at Sadman Cart</h1><p>Join the fastest-growing e-commerce platform in Bangladesh! We are always looking for passionate minds to join our product, engineering, logistics, and customer experience teams.</p><p>Send your updated CV and portfolio to careers@sadmancart.com and let’s build the future of commerce together.</p>',
            ],

            // Consumer Policy Group
            [
                'title' => 'Happy Return',
                'group' => 'consumer_policy',
                'description' => 'Learn how to easily return any purchased items within 7 days of package delivery with zero hassles.',
                'content' => '<h1>Happy Return Policy</h1><p>We want you to love your purchase! If you are not satisfied, you can initiate a happy return within 7 days of receiving the item.</p><p>The product must be unused, in its original packaging, and with all tags intact to be eligible for return.</p>',
            ],
            [
                'title' => 'Refund Policy',
                'group' => 'consumer_policy',
                'description' => 'Find out about refund processing times, return checkups, and mobile bank refund channels.',
                'content' => '<h1>Refund Policy</h1><p>Refunds are processed within 5-7 working days after we receive and inspect the returned item.</p><p>Refunds will be credited to your original payment method (Mobile Banking, Credit Card, or Cash on Delivery refund bank transfer).</p>',
            ],
            [
                'title' => 'Exchange Policy',
                'group' => 'consumer_policy',
                'description' => 'Change colors, sizes, or switch to other items easily using our simple 7-day exchange program.',
                'content' => '<h1>Exchange Policy</h1><p>Want a different size, color, or a different product? We offer easy exchange options within 7 days of delivery.</p><p>Simply contact our customer support, and we will arrange the exchange shipment for you.</p>',
            ],
            [
                'title' => 'Cancellation',
                'group' => 'consumer_policy',
                'description' => 'Guidance on order cancellations, active order reversals, and returns on delivered packages.',
                'content' => '<h1>Order Cancellation</h1><p>You can cancel your order at any time before it is shipped. Once shipped, cancellations cannot be processed directly, and you will have to request a return upon delivery.</p><p>To cancel, go to your order dashboard or call our helpline immediately.</p>',
            ],
            [
                'title' => 'Pre Order Support',
                'group' => 'consumer_policy',
                'description' => 'Secure upcoming surgical gears, smart gadgets, and trending accessories using partial advance booking.',
                'content' => '<h1>Pre-Order Support</h1><p>Get exclusive early access to upcoming hot products! Pre-orders require a partial advance payment to secure your booking.</p><p>Estimated delivery times are listed on the product page, and our pre-order support team will keep you updated every step of the way.</p>',
            ],

            // Support Group
            [
                'title' => 'Support Center',
                'group' => 'support',
                'description' => 'Visit the Sadman Cart help desk to connect with representatives, resolve shipping concerns, and file queries.',
                'content' => '<h1>Support Center</h1><p>Need help? Our support center is dedicated to answering all your queries. You can browse our FAQs, track your order status, or get in touch with a customer support representative.</p><p>We are available from 9 AM to 10 PM every day.</p>',
            ],
            [
                'title' => 'How to Order',
                'group' => 'support',
                'description' => 'Easy step-by-step ordering manual for checkouts, delivery scheduling, and secure online checkouts.',
                'content' => '<h1>How to Order</h1><p>Ordering from Sadman Cart is super easy:</p><ol><li>Browse our products or look for combo packs.</li><li>Add items to your cart.</li><li>Click "Checkout", fill in your delivery details, and choose Cash on Delivery or online payment.</li><li>Confirm your order and we will handle the rest!</li></ol>',
            ],
            [
                'title' => 'Order Tracking',
                'group' => 'support',
                'description' => 'Input your unique order ID or phone number to trace your package location and delivery ETA.',
                'content' => '<h1>Order Tracking</h1><p>Keep tab of your shipments! Enter your Order ID on our tracking page to get real-time status updates.</p><p>You will also receive SMS updates as your package moves from our warehouse to your doorstep.</p>',
            ],
            [
                'title' => 'Shipping Info',
                'group' => 'support',
                'description' => 'Find out about courier shipping timelines, express packaging, and local shipping fees.',
                'content' => '<h1>Shipping Information</h1><p>We deliver nationwide across Bangladesh!</p><ul><li><strong>Inside Dhaka:</strong> 1-2 days delivery, charge: ৳60.</li><li><strong>Outside Dhaka:</strong> 3-5 days delivery, charge: ৳120.</li></ul><p>All items are securely packaged to prevent damage during transit.</p>',
            ],
            [
                'title' => 'FAQ',
                'group' => 'support',
                'description' => 'Commonly asked questions and answers about surgical certifications, mobile banking payments, and delivery charges.',
                'content' => '<h1>Frequently Asked Questions (FAQ)</h1><h3>Q: Can I pay with bKash?</h3><p>A: Yes, we support bKash, Rocket, Nagad, and Cash on Delivery.</p><h3>Q: What is the delivery time?</h3><p>A: 1-2 days inside Dhaka, and 3-5 days outside Dhaka.</p><h3>Q: How do I request a refund?</h3><p>A: Go to return policy page or contact our support team at +880 1712-345678.</p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title' => $p['title'],
                    'group' => $p['group'],
                    'description' => $p['description'],
                    'image' => null,
                    'content' => $p['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
