# Localization Documentation

## Overview
The Atraf system now supports full localization in English and Arabic.

## Files Created

### 1. Middleware
- **File**: `app/Http/Middleware/ShareActiveRoutes.php`
- **Purpose**: Shares the $activeRoutes variable with all views to manage sidebar active state
- **Registered in**: `app/Http/Kernel.php` (web middleware group)

### 2. Language Files

#### English (lang/en/messages.php)
Contains all English translations for the application.

#### Arabic (lang/ar/messages.php)
Contains all Arabic translations for the application (RTL support).

## Usage in Blade Templates

Use the translation helper function:
```blade
{{ __('messages.key_name') }}
```

### Examples:
```blade
<!-- Simple text -->
<h3>{{ __('messages.users') }}</h3>

<!-- In attributes -->
<input placeholder="{{ __('messages.search') }}...">

<!-- Dynamic keys (for days) -->
{{ __('messages.' . $day) }}

<!-- With parameters (future use) -->
{{ __('messages.welcome', ['name' => $user->name]) }}
```

## Available Translation Keys

### Navigation & Menu
- `user_management` - User Management section
- `competitions_section` - Competitions section
- `points_rewards` - Points & Rewards section
- `system_settings` - System Settings section
- `content_management` - Content Management section

### User Module
- `users` - Users
- `admin_users` - Admin Users
- `user_details` - User Details
- `user_statistics` - User Statistics

### Father Schedules
- `father_schedules` - Father Schedules
- `create_schedule` - Create Schedule
- `father` - Father
- `day` - Day
- `from` - From
- `to` - To
- `slot_duration` - Slot Duration
- `minutes` - Minutes

### Days of Week
- `sunday` - Sunday / الأحد
- `monday` - Monday / الاثنين
- `tuesday` - Tuesday / الثلاثاء
- `wednesday` - Wednesday / الأربعاء
- `thursday` - Thursday / الخميس
- `friday` - Friday / الجمعة
- `saturday` - Saturday / السبت

### Atraf (Confessions)
- `atraf` - Confessions
- `etraf` - Confession
- `a3traf` - Confessions
- `create_etraf` - Create Confession
- `etraf_details` - Confession Details
- `total_atraf` - Total Confessions
- `completed_atraf` - Completed Confessions
- `last_etraf_date` - Last Confession Date

### Status
- `pending` - Pending / معلق
- `waiting` - Waiting / في الانتظار
- `completed` - Completed / مكتمل
- `set_pending` - Set Pending
- `set_waiting` - Set Waiting
- `set_completed` - Set Completed

### Common Actions
- `search` - Search
- `filter` - Filter
- `view` - View
- `edit` - Edit
- `delete` - Delete
- `create` - Create
- `save` - Save
- `cancel` - Cancel
- `back` - Back
- `logout` - Logout
- `actions` - Actions

### Messages
- `no_data_found` - No data found
- `please_search` - Please search for data
- `success_created` - Created successfully
- `success_updated` - Updated successfully
- `success_deleted` - Deleted successfully
- `error_occurred` - An error occurred
- `confirm` - Are you sure?

### Families
- `families` - Families
- `family_code` - Family Code
- `members_count` - Members Count
- `membership_code` - Membership Code
- `family_report` - Family Report

### Reports
- `reports` - Reports
- `user_report` - User Report
- `daily_report` - Daily Report
- `export_pdf` - Export PDF

## Changing Language

To change the application language, update the `.env` file:

```env
# For English
APP_LOCALE=en

# For Arabic
APP_LOCALE=ar
```

Or programmatically in your controller:
```php
app()->setLocale('ar'); // Switch to Arabic
app()->setLocale('en'); // Switch to English
```

## Adding New Translations

1. Add the key to both language files:
   - `lang/en/messages.php`
   - `lang/ar/messages.php`

2. Use the key in your blade template:
   ```blade
   {{ __('messages.your_new_key') }}
   ```

### Example:
```php
// lang/en/messages.php
return [
    // ... existing keys
    'new_feature' => 'New Feature',
];

// lang/ar/messages.php
return [
    // ... existing keys
    'new_feature' => 'ميزة جديدة',
];
```

## RTL Support

The layout automatically detects the language and applies RTL styling:

```blade
<html lang="{{ app()->getLocale() }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
```

## Active Routes Feature

The `$activeRoutes` variable is now automatically available in all views through the `ShareActiveRoutes` middleware. It contains boolean values for each route group:

```php
$activeRoutes = [
    'users' => true/false,
    'father-schedules' => true/false,
    'atraf' => true/false,
    'families' => true/false,
    // ... etc
];
```

Used in sidebar for active menu highlighting:
```blade
<a href="{{ route('users.index') }}" class="{{ $activeRoutes['users'] ? 'active' : '' }}">
    <i class="fas fa-users"></i>{{ __('messages.users') }}
</a>
```

## Benefits

1. **Easy Maintenance**: All text in one place
2. **Multi-language Support**: Switch between English and Arabic instantly
3. **RTL Support**: Automatic right-to-left layout for Arabic
4. **Consistent UX**: Same terminology across the application
5. **Scalability**: Easy to add more languages

## Best Practices

1. Always use translation keys, never hardcode text in views
2. Use descriptive key names (e.g., `create_schedule` not `btn1`)
3. Group related keys together in the language files
4. Keep translations consistent across the application
5. Test both English and Arabic views after changes
