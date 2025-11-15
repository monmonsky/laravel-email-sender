# Email Campaign Manager

A powerful and modern email campaign management system built with Laravel and Vue.js. Send personalized bulk emails, track delivery status, and manage your email campaigns with ease.

## Features

### Campaign Management
- **Create & Manage Campaigns** - Design and organize email campaigns with an intuitive interface
- **Email Templates** - Create reusable email templates with rich text editor (Quill.js)
- **Contact Lists** - Organize contacts into lists for targeted campaigns
- **Multiple Senders** - Configure and manage multiple SMTP senders
- **Variable Substitution** - Personalize emails with `{{name}}`, `{{email}}`, `{{phone}}` variables
- **Draft & Schedule** - Save campaigns as drafts or schedule for later sending

### Email Delivery
- **Queue-Based Sending** - Efficient email sending using Laravel Queue with database driver
- **Background Processing** - Send emails asynchronously without blocking the application
- **Supervisor Integration** - Auto-restart workers for reliable email delivery
- **Multiple SMTP Support** - Configure different SMTP servers per campaign
- **Batch Processing** - Send to large contact lists efficiently

### Tracking & Analytics
- **Delivery Status Tracking** - Monitor sent, pending, and failed emails
- **Email Status Dashboard** - View comprehensive statistics and delivery metrics
- **Per-Contact History** - Track all emails sent to individual contacts
- **Per-Campaign Reports** - Detailed delivery reports for each campaign
- **Open & Click Tracking** - Track email opens and link clicks (when implemented)
- **Error Logging** - Detailed error messages for failed deliveries

### User Interface
- **Modern UI** - Built with Vue.js 3 and Inertia.js
- **Dark Mode Support** - Full dark mode compatibility
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile
- **Rich Text Editor** - Quill.js integration for beautiful email content
- **Image Library** - Upload and manage images for email campaigns
- **Real-time Filtering** - Advanced search and filtering capabilities

## Tech Stack

### Backend
- **Laravel 11** - PHP framework
- **PostgreSQL** - Database
- **Queue System** - Laravel Queue with database driver
- **Supervisor** - Process management for queue workers

### Frontend
- **Vue.js 3** - JavaScript framework
- **Inertia.js** - Modern monolith approach
- **Tailwind CSS** - Utility-first CSS framework
- **Quill.js** - Rich text editor

## Requirements

- PHP 8.2 or higher
- PostgreSQL 13 or higher
- Composer
- Node.js & NPM
- Supervisor (for queue workers)

## Installation

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd email-sender
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Install JavaScript dependencies
```bash
npm install
```

### 4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure database
Edit `.env` file:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=email_sender
DB_USERNAME=your_username
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
```

### 6. Run migrations
```bash
php artisan migrate
```

### 7. Build assets
```bash
npm run build
# or for development
npm run dev
```

### 8. Configure Supervisor for Queue Workers

Copy the supervisor configuration:
```bash
sudo cp email-sender-worker.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start email-sender-worker:*
```

See [SUPERVISOR_SETUP.md](SUPERVISOR_SETUP.md) for detailed queue worker setup instructions.

## Configuration

### SMTP Senders

1. Navigate to **Senders** in the application
2. Click **Create Sender**
3. Fill in SMTP configuration:
   - Name: Display name for the sender
   - From Email: Email address to send from
   - From Name: Name that appears in recipient's inbox
   - SMTP Host, Port, Username, Password
   - Encryption: TLS/SSL
   - Set as Default (optional)

### Email Templates

1. Navigate to **Templates**
2. Create reusable templates with subject and content
3. Use variables: `{{name}}`, `{{email}}`, `{{phone}}`
4. Templates can be selected when creating campaigns

### Contact Lists

1. Navigate to **Contacts**
2. Create contact lists
3. Add contacts individually or import (if implemented)
4. Organize contacts by list for targeted campaigns

## Usage

### Creating a Campaign

1. Navigate to **Campaigns** → **Create Campaign**
2. Enter campaign details:
   - Campaign Name
   - Select Contact List
   - Choose Email Template (optional)
   - Select Email Sender (optional - uses default if not specified)
   - Email Subject (supports variables)
   - Email Content (rich text editor, supports variables)
   - Status: Draft, Scheduled, or Sent
3. Click **Create Campaign**

### Sending a Campaign

1. Navigate to **Campaigns**
2. Find your campaign
3. Click **Send** button
4. Confirm sending
5. Emails are queued and sent in background

### Monitoring Email Status

1. Navigate to **Email Status**
2. View statistics: Total, Sent, Pending, Failed, Opened, Clicked
3. Filter by:
   - Campaign
   - Status
   - Contact name/email
4. Click on campaign or contact for detailed reports

## Queue Management

### Start Queue Worker
```bash
php artisan queue:work database --sleep=3 --tries=3
```

### Monitor Queue
```bash
php artisan queue:failed     # View failed jobs
php artisan queue:retry all  # Retry failed jobs
php artisan queue:flush      # Clear failed jobs
```

### Supervisor Status
```bash
sudo supervisorctl status
sudo supervisorctl restart email-sender-worker:*
```

## Development

### Run development server
```bash
php artisan serve
npm run dev
```

### Database seeding (if seeders are implemented)
```bash
php artisan db:seed
```

### Clear caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Project Structure

```
email-sender/
├── app/
│   ├── Http/Controllers/
│   │   ├── CampaignController.php
│   │   ├── ContactController.php
│   │   ├── EmailStatusController.php
│   │   ├── SenderController.php
│   │   └── TemplateController.php
│   ├── Models/
│   │   ├── Campaign.php
│   │   ├── CampaignLog.php
│   │   ├── Contact.php
│   │   ├── ContactList.php
│   │   ├── Sender.php
│   │   └── Template.php
│   └── Jobs/
│       └── SendCampaignEmail.php
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Campaigns/
│   │   │   ├── Contacts/
│   │   │   ├── EmailStatus/
│   │   │   ├── Senders/
│   │   │   └── Templates/
│   │   └── Layouts/
│   └── views/
├── database/
│   └── migrations/
├── routes/
│   └── web.php
└── public/
```

## API Endpoints

The application uses Inertia.js for seamless SPA experience. Key routes:

- `/campaigns` - Campaign management
- `/contacts` - Contact management
- `/templates` - Template management
- `/senders` - SMTP sender configuration
- `/email-status` - Email delivery tracking
- `/settings` - Application settings

## Features Roadmap

- [ ] Email open tracking with pixel
- [ ] Click tracking with link rewriting
- [ ] A/B testing campaigns
- [ ] CSV import for contacts
- [ ] Advanced analytics dashboard
- [ ] Email bounce handling
- [ ] Unsubscribe management
- [ ] Scheduled campaigns
- [ ] Recurring campaigns
- [ ] Multi-user support with permissions
- [ ] API for external integrations

## Security

- SMTP credentials are encrypted in database
- CSRF protection on all forms
- XSS protection with Vue.js
- SQL injection protection with Eloquent ORM
- Authentication with Laravel Breeze
- Rate limiting on email sending

## Troubleshooting

### Emails not sending
1. Check queue worker is running: `sudo supervisorctl status`
2. Check failed jobs: `php artisan queue:failed`
3. Verify SMTP configuration in Senders
4. Check logs: `storage/logs/laravel.log`

### Permission errors
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database connection errors
- Verify PostgreSQL is running
- Check `.env` database credentials
- Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or suggestions, please create an issue in the GitHub repository.

---

**Built with ❤️ using Laravel, Vue.js, and Inertia.js**
