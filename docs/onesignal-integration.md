# OneSignal Integration Summary (bet4d_new)

This document summarizes how OneSignal push notifications are implemented in this project so it can be replicated elsewhere.

## Overview
- Notifications are sent via a queued job and a custom channel.
- Each notification is stored in the database and then pushed through OneSignal using external user IDs.

## Key Files
- [app/Traits/NotificationTrait.php](app/Traits/NotificationTrait.php)
  - Entry point: `sendNotification($appId, $apiKey, $recipient, $message, $module = null)`
  - Dispatches `SendNotification` job.
- [app/Jobs/SendNotification.php](app/Jobs/SendNotification.php)
  - Creates DB notification via `$recipient->notifications()->create(...)`.
  - Optionally associates a polymorphic target (`$module`).
  - Calls `OnesignalChannel::send(...)`.
- [app/Notifications/Channels/OnesignalChannel.php](app/Notifications/Channels/OnesignalChannel.php)
  - Builds payload and sends via `OneSignal::sendNotificationCustom($params)`.
  - Uses `include_external_user_ids` with `$notifiable->id`.

## Configuration
- OneSignal package registered in [config/app.php](config/app.php):
  - `Berkayk\OneSignal\OneSignalServiceProvider::class`
  - Facade: `OneSignal` => `Berkayk\OneSignal\OneSignalFacade::class`
- App IDs and API keys are currently stored in [config/app.php](config/app.php) (hardcoded values).
  - Recommendation: move these to `.env` and access via `env(...)` to avoid committing secrets.

## Runtime Flow
1. Application calls `sendNotification(...)` (via `NotificationTrait`).
2. `SendNotification` job runs on queue:
   - Creates a notification record in DB.
   - Adds optional targetable model data.
3. `OnesignalChannel::send(...)`:
   - Creates `headings` (title) and `contents` (message) payload.
   - Sends via OneSignal with `include_external_user_ids`.

## Payload Example (as built in `OnesignalChannel::send`)
```
$params = [
  'app_id' => $appId,
  'headings' => ['en' => $title],
  'contents' => ['en' => $message],
  'include_external_user_ids' => [$notifiable->id],
  'api_key' => $apiKey,
];
```

## Queue Requirements
- `SendNotification` implements `ShouldQueue`.
- Ensure a queue worker is running in the target project.

## What To Copy Into Another Project
1. `NotificationTrait` (or integrate into existing notification service).
2. `SendNotification` job.
3. `OnesignalChannel` custom channel.
4. OneSignal package registration + configuration.
5. Database notifications relationship on the notifiable model.

## Notes
- This implementation uses **OneSignal external user IDs** = local user ID.
- Ensure your OneSignal app is configured to accept external user IDs.
- Consider adding `url` or `data` if the other project needs deep links.
- Consider moving keys to `.env` and using `config('services.onesignal')`.
