<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sender;

class SenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $senders = [
            [
                'name' => 'Default Gmail SMTP',
                'from_email' => 'your-email@gmail.com',
                'from_name' => 'Your Company',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_username' => 'your-email@gmail.com',
                'smtp_password' => 'your-app-password',
                'smtp_encryption' => 'tls',
                'is_default' => true,
            ],
            [
                'name' => 'Mailgun SMTP',
                'from_email' => 'noreply@yourdomain.com',
                'from_name' => 'Your Company',
                'smtp_host' => 'smtp.mailgun.org',
                'smtp_port' => 587,
                'smtp_username' => 'postmaster@yourdomain.com',
                'smtp_password' => 'your-mailgun-password',
                'smtp_encryption' => 'tls',
                'is_default' => false,
            ],
            [
                'name' => 'SendGrid SMTP',
                'from_email' => 'noreply@yourdomain.com',
                'from_name' => 'Your Company',
                'smtp_host' => 'smtp.sendgrid.net',
                'smtp_port' => 587,
                'smtp_username' => 'apikey',
                'smtp_password' => 'your-sendgrid-api-key',
                'smtp_encryption' => 'tls',
                'is_default' => false,
            ],
        ];

        foreach ($senders as $sender) {
            Sender::create($sender);
        }
    }
}
