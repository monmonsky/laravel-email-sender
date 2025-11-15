<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to Our Platform, {{name}}!',
                'body' => '<h2>Welcome {{name}}!</h2><p>Thank you for joining our platform. We\'re excited to have you on board!</p><p>Here\'s what you can do next:</p><ul><li>Complete your profile</li><li>Explore our features</li><li>Connect with other users</li></ul><p>If you have any questions, feel free to reply to this email at {{email}}.</p><p>Best regards,<br>The Team</p>',
            ],
            [
                'name' => 'Newsletter Template',
                'subject' => 'Monthly Newsletter - {{name}}',
                'body' => '<h1>Hi {{name}},</h1><p>Here\'s what\'s new this month:</p><h3>Product Updates</h3><p>We\'ve rolled out several exciting new features that we think you\'ll love. Check out our latest blog post to learn more about what\'s new.</p><h3>Tips & Tricks</h3><p>Did you know you can customize your dashboard? Visit your settings to personalize your experience.</p><h3>Community Highlights</h3><p>Join the conversation in our community forum and connect with other members.</p><p>Thank you for being part of our community!</p><p>Best,<br>The Team</p>',
            ],
            [
                'name' => 'Promotional Offer',
                'subject' => 'Special Offer Just for You, {{name}}!',
                'body' => '<h2>Hey {{name}},</h2><p><strong>Limited Time Offer!</strong></p><p>As a valued member, we\'re offering you an exclusive <strong>20% discount</strong> on all premium plans.</p><div style="background-color: #f0f0f0; padding: 20px; margin: 20px 0; text-align: center;"><h3 style="margin: 0;">Use Code: <span style="color: #4f46e5;">SAVE20</span></h3></div><p>This offer expires in 7 days, so don\'t miss out!</p><p><a href="#" style="background-color: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">Claim Your Discount</a></p><p>Questions? Contact us at {{email}}</p>',
            ],
            [
                'name' => 'Event Invitation',
                'subject' => 'You\'re Invited: Exclusive Webinar for {{name}}',
                'body' => '<h2>Hi {{name}},</h2><p>You\'re invited to our exclusive webinar!</p><h3>Event Details:</h3><ul><li><strong>Topic:</strong> Advanced Email Marketing Strategies</li><li><strong>Date:</strong> Next Friday, 2:00 PM EST</li><li><strong>Duration:</strong> 1 hour</li><li><strong>Speaker:</strong> Industry Expert</li></ul><p>In this session, you\'ll learn:</p><ul><li>How to boost email open rates</li><li>Best practices for segmentation</li><li>A/B testing strategies</li><li>Automation workflows that convert</li></ul><p><a href="#" style="background-color: #059669; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">Reserve Your Spot</a></p><p>Limited seats available. Register now!</p>',
            ],
            [
                'name' => 'Account Verification',
                'subject' => 'Please Verify Your Email Address',
                'body' => '<h2>Hello {{name}},</h2><p>Thank you for creating an account with us!</p><p>To get started, please verify your email address by clicking the button below:</p><div style="text-align: center; margin: 30px 0;"><a href="#" style="background-color: #4f46e5; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">Verify Email Address</a></div><p>This link will expire in 24 hours.</p><p>If you didn\'t create an account, please ignore this email.</p><p>Contact: {{email}}<br>Phone: {{phone}}</p>',
            ],
            [
                'name' => 'Password Reset',
                'subject' => 'Reset Your Password',
                'body' => '<h2>Password Reset Request</h2><p>Hi {{name}},</p><p>We received a request to reset your password. Click the button below to create a new password:</p><div style="text-align: center; margin: 30px 0;"><a href="#" style="background-color: #dc2626; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">Reset Password</a></div><p>If you didn\'t request a password reset, please ignore this email or contact support if you have concerns.</p><p>This link expires in 1 hour.</p><p>Support: {{email}}</p>',
            ],
            [
                'name' => 'Order Confirmation',
                'subject' => 'Order Confirmation - Thank You {{name}}!',
                'body' => '<h2>Thank You for Your Order!</h2><p>Hi {{name}},</p><p>We\'ve received your order and it\'s being processed.</p><h3>Order Details:</h3><table style="width: 100%; border-collapse: collapse;"><tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Order Number:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">#12345</td></tr><tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Order Date:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">Today</td></tr><tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Total:</strong></td><td style="padding: 10px; border: 1px solid #ddd;">$99.00</td></tr></table><p>You\'ll receive a shipping confirmation email once your order ships.</p><p>Questions? Email: {{email}} | Phone: {{phone}}</p>',
            ],
            [
                'name' => 'Feedback Request',
                'subject' => 'We\'d Love Your Feedback, {{name}}',
                'body' => '<h2>Hi {{name}},</h2><p>How are we doing?</p><p>Your opinion matters to us! We\'d appreciate if you could take 2 minutes to share your feedback.</p><div style="text-align: center; margin: 30px 0;"><a href="#" style="background-color: #0891b2; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">Share Your Feedback</a></div><p>Your insights help us improve and serve you better.</p><p>As a thank you, you\'ll be entered into our monthly draw to win a $50 gift card!</p><p>Thank you for your time,<br>The Team</p>',
            ],
            [
                'name' => 'Subscription Renewal',
                'subject' => 'Your Subscription Renewal - {{name}}',
                'body' => '<h2>Hello {{name}},</h2><p>This is a friendly reminder that your subscription will renew on <strong>January 1st, 2024</strong>.</p><h3>Subscription Details:</h3><ul><li><strong>Plan:</strong> Premium</li><li><strong>Amount:</strong> $29.99/month</li><li><strong>Payment Method:</strong> •••• 1234</li></ul><p>No action is needed - your subscription will renew automatically.</p><p>Want to make changes?</p><p><a href="#" style="color: #4f46e5; text-decoration: none;">Manage Your Subscription</a></p><p>Questions? Contact us at {{email}}</p>',
            ],
            [
                'name' => 'Thank You Message',
                'subject' => 'Thank You, {{name}}!',
                'body' => '<h2>Dear {{name}},</h2><p><strong>Thank you!</strong></p><p>We wanted to take a moment to express our gratitude for your continued support and loyalty.</p><p>Customers like you make what we do possible, and we\'re honored to serve you.</p><div style="background-color: #fef3c7; padding: 20px; margin: 20px 0; border-left: 4px solid #f59e0b;"><p style="margin: 0;"><strong>Special Thank You Gift:</strong> Use code <strong>THANKYOU10</strong> for 10% off your next purchase!</p></div><p>We look forward to continuing to serve you.</p><p>With gratitude,<br>The Team<br>{{email}} | {{phone}}</p>',
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}
