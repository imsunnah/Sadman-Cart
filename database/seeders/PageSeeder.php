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
                'title_en' => 'About Us', 'title_bn' => 'আমাদের সম্পর্কে',
                'group' => 'about_us',
                'description_en' => 'Learn more about Sadman Cart, our core mission, operational vision, and how we deliver top surgical products and smart electronics.',
                'description_bn' => 'সাদমান কার্ট, আমাদের মূল লক্ষ্য এবং কীভাবে আমরা শীর্ষ সার্জিক্যাল পণ্য সরবরাহ করি সে সম্পর্কে আরও জানুন।',
                'content_en' => '<h1>About Sadman Cart</h1><p>Welcome to Sadman Cart, your ultimate destination for high-quality Surgical Products, Smart Electronics, and Premium Accessories. We are committed to bringing the best products directly to your doorstep with unmatched service.</p><p>Founded in 2026, we aim to provide an exceptional e-commerce experience with dynamic pricing, transparent discounts, and fast logistics across Bangladesh.</p>',
                'content_bn' => '<h1>সাদমান কার্ট সম্পর্কে</h1><p>সাদমান কার্টে স্বাগতম, উচ্চ মানের সার্জিক্যাল পণ্য, স্মার্ট ইলেকট্রনিক্স এবং প্রিমিয়াম এক্সেসরিজের জন্য আপনার চূড়ান্ত গন্তব্য। আমরা অতুলনীয় পরিষেবার সাথে আপনার দোরগোড়ায় সেরা পণ্যগুলি নিয়ে আসতে প্রতিশ্রুতিবদ্ধ।</p><p>২০২৬ সালে প্রতিষ্ঠিত, আমরা সারা বাংলাদেশে একটি ব্যতিক্রমী ই-কমার্স অভিজ্ঞতা প্রদানের লক্ষ্য রাখি।</p>',
            ],
            [
                'title_en' => 'Contact Us', 'title_bn' => 'যোগাযোগ করুন',
                'group' => 'about_us',
                'description_en' => 'Get in touch with Sadman Cart customer care via phone, support email, or office address. We are here to support you 24/7.',
                'description_bn' => 'ফোন, সাপোর্ট ইমেইল বা অফিস ঠিকানার মাধ্যমে সাদমান কার্ট কাস্টমার কেয়ারের সাথে যোগাযোগ করুন। আমরা আপনাকে সমর্থন করতে 24/7 এখানে আছি।',
                'content_en' => '<h1>Contact Us</h1><p>We are here to assist you 24/7. Get in touch with us through any of the channels below:</p><ul><li><strong>Phone:</strong> +880 1712-345678</li><li><strong>Email:</strong> support@sadmancart.com</li><li><strong>Address:</strong> Road 12, Banani, Dhaka, Bangladesh</li></ul><p>Or send us a message on our Facebook page for instant support!</p>',
                'content_bn' => '<h1>যোগাযোগ করুন</h1><p>আমরা আপনাকে 24/7 সহায়তা করতে এখানে আছি। নিচের যেকোনো মাধ্যমে আমাদের সাথে যোগাযোগ করুন:</p><ul><li><strong>ফোন:</strong> +880 1712-345678</li><li><strong>ইমেইল:</strong> support@sadmancart.com</li><li><strong>ঠিকানা:</strong> সড়ক ১২, বনানী, ঢাকা, বাংলাদেশ</li></ul><p>অথবা তাত্ক্ষণিক সহায়তার জন্য আমাদের ফেসবুক পেজে একটি বার্তা পাঠান!</p>',
            ],
            [
                'title_en' => 'Terms & Conditions', 'title_bn' => 'শর্তাবলী',
                'group' => 'about_us',
                'description_en' => 'Read the terms, conditions, and legal agreements required to browse, interact, and buy on the Sadman Cart portal.',
                'description_bn' => 'সাদমান কার্ট পোর্টালে ব্রাউজ, ইন্টারঅ্যাক্ট এবং কেনার জন্য প্রয়োজনীয় শর্তাবলী এবং আইনি চুক্তিগুলি পড়ুন।',
                'content_en' => '<h1>Terms & Conditions</h1><p>Please read these Terms & Conditions carefully before using our platform. By accessing or using Sadman Cart, you agree to be bound by these terms.</p><p>We reserve the right to update or modify these terms at any time without prior notice. Your continued use of the platform constitutes agreement to the updated terms.</p>',
                'content_bn' => '<h1>শর্তাবলী</h1><p>আমাদের প্ল্যাটফর্ম ব্যবহার করার আগে দয়া করে এই শর্তাবলী সাবধানে পড়ুন। সাদমান কার্ট ব্যবহার করে, আপনি এই শর্তাবলীতে আবদ্ধ হতে সম্মত হন।</p><p>আমরা পূর্ব ঘোষণা ছাড়াই যেকোনো সময় এই শর্তাবলী আপডেট বা পরিবর্তন করার অধিকার সংরক্ষণ করি।</p>',
            ],
            [
                'title_en' => 'Privacy Policy', 'title_bn' => 'গোপনীয়তা নীতি',
                'group' => 'about_us',
                'description_en' => 'Understand how we protect, store, and process your personal data, secure payment transcripts, and delivery addresses.',
                'description_bn' => 'আমরা কীভাবে আপনার ব্যক্তিগত তথ্য সুরক্ষা, সংরক্ষণ এবং প্রক্রিয়া করি তা বুঝুন।',
                'content_en' => '<h1>Privacy Policy</h1><p>At Sadman Cart, we value your privacy. We collect, store, and process your personal data (name, email, phone number, shipping address) solely to fulfill your orders and enhance your user experience.</p><p>We will never sell or share your data with unauthorized third parties. Secure payment processing is handled through certified gateways.</p>',
                'content_bn' => '<h1>গোপনীয়তা নীতি</h1><p>সাদমান কার্টে, আমরা আপনার গোপনীয়তাকে মূল্য দিই। আমরা আপনার অর্ডার পূরণ করতে এবং আপনার ব্যবহারকারীর অভিজ্ঞতা উন্নত করতে আপনার ব্যক্তিগত ডেটা সংগ্রহ, সঞ্চয় এবং প্রক্রিয়া করি।</p><p>আমরা কখনই আপনার ডেটা অননুমোদিত তৃতীয় পক্ষের সাথে বিক্রি বা শেয়ার করব না।</p>',
            ],
            [
                'title_en' => 'Careers', 'title_bn' => 'ক্যারিয়ার',
                'group' => 'about_us',
                'description_en' => 'Explore current openings, build the future of e-commerce, and join our creative product, engineering, and logistics teams.',
                'description_bn' => 'বর্তমান সুযোগগুলি অন্বেষণ করুন এবং আমাদের টিমে যোগ দিন।',
                'content_en' => '<h1>Careers at Sadman Cart</h1><p>Join the fastest-growing e-commerce platform in Bangladesh! We are always looking for passionate minds to join our product, engineering, logistics, and customer experience teams.</p><p>Send your updated CV and portfolio to careers@sadmancart.com and let’s build the future of commerce together.</p>',
                'content_bn' => '<h1>সাদমান কার্টে ক্যারিয়ার</h1><p>বাংলাদেশের দ্রুত বর্ধনশীল ই-কমার্স প্ল্যাটফর্মে যোগ দিন! আমরা সবসময় আমাদের টিমে যোগদানের জন্য আগ্রহী মন খুঁজছি।</p><p>আপনার আপডেট করা সিভি careers@sadmancart.com এ পাঠান।</p>',
            ],
            // Consumer Policy Group
            [
                'title_en' => 'Happy Return', 'title_bn' => 'সহজ রিটার্ন',
                'group' => 'consumer_policy',
                'description_en' => 'Learn how to easily return any purchased items within 7 days of package delivery with zero hassles.',
                'description_bn' => 'কীভাবে প্যাকেজ ডেলিভারির ৭ দিনের মধ্যে কেনা পণ্য সহজেই ফেরত দেওয়া যায় তা জানুন।',
                'content_en' => '<h1>Happy Return Policy</h1><p>We want you to love your purchase! If you are not satisfied, you can initiate a happy return within 7 days of receiving the item.</p><p>The product must be unused, in its original packaging, and with all tags intact to be eligible for return.</p>',
                'content_bn' => '<h1>রিটার্ন পলিসি</h1><p>আপনি যদি সন্তুষ্ট না হন, আপনি আইটেমটি প্রাপ্তির ৭ দিনের মধ্যে একটি রিটার্ন শুরু করতে পারেন।</p><p>পণ্যটি অবশ্যই অব্যবহৃত, তার আসল প্যাকেজিংয়ে এবং সমস্ত ট্যাগ সহ অক্ষত থাকতে হবে।</p>',
            ],
            [
                'title_en' => 'Refund Policy', 'title_bn' => 'রিফান্ড পলিসি',
                'group' => 'consumer_policy',
                'description_en' => 'Find out about refund processing times, return checkups, and mobile bank refund channels.',
                'description_bn' => 'রিফান্ড প্রক্রিয়াকরণের সময় এবং মোবাইল ব্যাংক রিফান্ড চ্যানেল সম্পর্কে জানুন।',
                'content_en' => '<h1>Refund Policy</h1><p>Refunds are processed within 5-7 working days after we receive and inspect the returned item.</p><p>Refunds will be credited to your original payment method (Mobile Banking, Credit Card, or Cash on Delivery refund bank transfer).</p>',
                'content_bn' => '<h1>রিফান্ড পলিসি</h1><p>রিফান্ড আমাদের ফেরত দেওয়া আইটেম প্রাপ্তি এবং পরিদর্শনের ৫-৭ কার্যদিবসের মধ্যে প্রক্রিয়া করা হয়।</p><p>আপনার আসল অর্থপ্রদান পদ্ধতিতে রিফান্ড জমা করা হবে।</p>',
            ],
            [
                'title_en' => 'Exchange Policy', 'title_bn' => 'এক্সচেঞ্জ পলিসি',
                'group' => 'consumer_policy',
                'description_en' => 'Change colors, sizes, or switch to other items easily using our simple 7-day exchange program.',
                'description_bn' => 'আমাদের ৭ দিনের এক্সচেঞ্জ প্রোগ্রামের মাধ্যমে সহজেই রঙ, মাপ পরিবর্তন করুন।',
                'content_en' => '<h1>Exchange Policy</h1><p>Want a different size, color, or a different product? We offer easy exchange options within 7 days of delivery.</p><p>Simply contact our customer support, and we will arrange the exchange shipment for you.</p>',
                'content_bn' => '<h1>এক্সচেঞ্জ পলিসি</h1><p>একটি ভিন্ন আকার, রঙ, বা একটি ভিন্ন পণ্য চান? আমরা ডেলিভারির ৭ দিনের মধ্যে সহজ বিনিময় বিকল্প অফার করি।</p><p>শুধু আমাদের গ্রাহক সহায়তার সাথে যোগাযোগ করুন।</p>',
            ],
            [
                'title_en' => 'Cancellation', 'title_bn' => 'বাতিলকরণ',
                'group' => 'consumer_policy',
                'description_en' => 'Guidance on order cancellations, active order reversals, and returns on delivered packages.',
                'description_bn' => 'অর্ডার বাতিলকরণ এবং ডেলিভার করা প্যাকেজের রিটার্নের নির্দেশিকা।',
                'content_en' => '<h1>Order Cancellation</h1><p>You can cancel your order at any time before it is shipped. Once shipped, cancellations cannot be processed directly, and you will have to request a return upon delivery.</p><p>To cancel, go to your order dashboard or call our helpline immediately.</p>',
                'content_bn' => '<h1>অর্ডার বাতিলকরণ</h1><p>শিপ করার আগে আপনি যেকোনো সময় আপনার অর্ডার বাতিল করতে পারেন। একবার পাঠানো হলে, বাতিলকরণ সরাসরি প্রক্রিয়া করা যায় না।</p><p>বাতিল করতে, অবিলম্বে আমাদের হেল্পলাইনে কল করুন।</p>',
            ],
            [
                'title_en' => 'Pre Order Support', 'title_bn' => 'প্রি-অর্ডার সাপোর্ট',
                'group' => 'consumer_policy',
                'description_en' => 'Secure upcoming surgical gears, smart gadgets, and trending accessories using partial advance booking.',
                'description_bn' => 'আংশিক অগ্রিম বুকিং ব্যবহার করে আসন্ন পণ্য সুরক্ষিত করুন।',
                'content_en' => '<h1>Pre-Order Support</h1><p>Get exclusive early access to upcoming hot products! Pre-orders require a partial advance payment to secure your booking.</p><p>Estimated delivery times are listed on the product page, and our pre-order support team will keep you updated every step of the way.</p>',
                'content_bn' => '<h1>প্রি-অর্ডার সাপোর্ট</h1><p>আসন্ন হট পণ্যগুলিতে এক্সক্লুসিভ প্রাথমিক অ্যাক্সেস পান! বুকিং সুরক্ষিত করতে আংশিক অগ্রিম অর্থ প্রদানের প্রয়োজন।</p><p>আনুমানিক ডেলিভারির সময় পণ্যের পৃষ্ঠায় তালিকাভুক্ত করা হয়েছে।</p>',
            ],
            // Support Group
            [
                'title_en' => 'Support Center', 'title_bn' => 'সাপোর্ট সেন্টার',
                'group' => 'support',
                'description_en' => 'Visit the Sadman Cart help desk to connect with representatives, resolve shipping concerns, and file queries.',
                'description_bn' => 'সাদমান কার্ট হেল্প ডেস্কে যান এবং আপনার প্রশ্নের সমাধান করুন।',
                'content_en' => '<h1>Support Center</h1><p>Need help? Our support center is dedicated to answering all your queries. You can browse our FAQs, track your order status, or get in touch with a customer support representative.</p><p>We are available from 9 AM to 10 PM every day.</p>',
                'content_bn' => '<h1>সাপোর্ট সেন্টার</h1><p>সাহায্য প্রয়োজন? আমাদের সাপোর্ট সেন্টার আপনার সমস্ত প্রশ্নের উত্তর দেওয়ার জন্য নিবেদিত। আপনি আমাদের গ্রাহক সহায়তা প্রতিনিধির সাথে যোগাযোগ করতে পারেন।</p><p>আমরা প্রতিদিন সকাল ৯টা থেকে রাত ১০টা পর্যন্ত উপলব্ধ।</p>',
            ],
            [
                'title_en' => 'How to Order', 'title_bn' => 'কীভাবে অর্ডার করবেন',
                'group' => 'support',
                'description_en' => 'Easy step-by-step ordering manual for checkouts, delivery scheduling, and secure online checkouts.',
                'description_bn' => 'চেকআউট এবং ডেলিভারি শিডিউলিংয়ের জন্য সহজ অর্ডারিং ম্যানুয়াল।',
                'content_en' => '<h1>How to Order</h1><p>Ordering from Sadman Cart is super easy:</p><ol><li>Browse our products or look for combo packs.</li><li>Add items to your cart.</li><li>Click "Checkout", fill in your delivery details, and choose Cash on Delivery or online payment.</li><li>Confirm your order and we will handle the rest!</li></ol>',
                'content_bn' => '<h1>কীভাবে অর্ডার করবেন</h1><p>সাদমান কার্ট থেকে অর্ডার করা খুব সহজ:</p><ol><li>আমাদের পণ্য ব্রাউজ করুন।</li><li>আপনার কার্টে আইটেম যোগ করুন।</li><li>"চেকআউট" এ ক্লিক করুন এবং ক্যাশ অন ডেলিভারি বা অনলাইন পেমেন্ট বেছে নিন।</li><li>আপনার অর্ডার নিশ্চিত করুন!</li></ol>',
            ],
            [
                'title_en' => 'Order Tracking', 'title_bn' => 'অর্ডার ট্র্যাকিং',
                'group' => 'support',
                'description_en' => 'Input your unique order ID or phone number to trace your package location and delivery ETA.',
                'description_bn' => 'আপনার প্যাকেজের অবস্থান ট্র্যাক করতে আপনার অর্ডার আইডি বা ফোন নম্বর দিন।',
                'content_en' => '<h1>Order Tracking</h1><p>Keep tab of your shipments! Enter your Order ID on our tracking page to get real-time status updates.</p><p>You will also receive SMS updates as your package moves from our warehouse to your doorstep.</p>',
                'content_bn' => '<h1>অর্ডার ট্র্যাকিং</h1><p>আপনার চালানের হিসাব রাখুন! রিয়েল-টাইম স্ট্যাটাস আপডেট পেতে আমাদের ট্র্যাকিং পৃষ্ঠায় আপনার অর্ডার আইডি লিখুন।</p>',
            ],
            [
                'title_en' => 'Shipping Info', 'title_bn' => 'শিপিং ইনফো',
                'group' => 'support',
                'description_en' => 'Find out about courier shipping timelines, express packaging, and local shipping fees.',
                'description_bn' => 'কুরিয়ার শিপিং সময়সীমা এবং শিপিং ফি সম্পর্কে জানুন।',
                'content_en' => '<h1>Shipping Information</h1><p>We deliver nationwide across Bangladesh!</p><ul><li><strong>Inside Dhaka:</strong> 1-2 days delivery, charge: ৳60.</li><li><strong>Outside Dhaka:</strong> 3-5 days delivery, charge: ৳120.</li></ul><p>All items are securely packaged to prevent damage during transit.</p>',
                'content_bn' => '<h1>শিপিং তথ্য</h1><p>আমরা সারা বাংলাদেশে দেশব্যাপী ডেলিভারি করি!</p><ul><li><strong>ঢাকার ভিতরে:</strong> ১-২ দিন ডেলিভারি, চার্জ: ৳৬০।</li><li><strong>ঢাকার বাইরে:</strong> ৩-৫ দিন ডেলিভারি, চার্জ: ৳১২০।</li></ul>',
            ],
            [
                'title_en' => 'FAQ', 'title_bn' => 'সাধারণ প্রশ্নাবলী',
                'group' => 'support',
                'description_en' => 'Commonly asked questions and answers about surgical certifications, mobile banking payments, and delivery charges.',
                'description_bn' => 'পেমেন্ট এবং ডেলিভারি চার্জ সম্পর্কে সাধারণ প্রশ্ন ও উত্তর।',
                'content_en' => '<h1>Frequently Asked Questions (FAQ)</h1><h3>Q: Can I pay with bKash?</h3><p>A: Yes, we support bKash, Rocket, Nagad, and Cash on Delivery.</p><h3>Q: What is the delivery time?</h3><p>A: 1-2 days inside Dhaka, and 3-5 days outside Dhaka.</p><h3>Q: How do I request a refund?</h3><p>A: Go to return policy page or contact our support team at +880 1712-345678.</p>',
                'content_bn' => '<h1>সাধারণ প্রশ্নাবলী (FAQ)</h1><h3>প্রশ্ন: আমি কি বিকাশে পেমেন্ট করতে পারি?</h3><p>উত্তর: হ্যাঁ, আমরা বিকাশ, রকেট, নগদ এবং ক্যাশ অন ডেলিভারি সমর্থন করি।</p><h3>প্রশ্ন: ডেলিভারি সময় কত?</h3><p>উত্তর: ঢাকার ভিতরে ১-২ দিন এবং ঢাকার বাইরে ৩-৫ দিন।</p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['slug' => Str::slug($p['title_en'])],
                [
                    'title_en' => $p['title_en'],
                    'title_bn' => $p['title_bn'],
                    'group' => $p['group'],
                    'description_en' => $p['description_en'],
                    'description_bn' => $p['description_bn'],
                    'image' => null,
                    'content_en' => $p['content_en'],
                    'content_bn' => $p['content_bn'],
                    'is_active' => true,
                ]
            );
        }
    }
}
