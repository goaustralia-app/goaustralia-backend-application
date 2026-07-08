# EOI Tracking Module — Implementation Plan

## Status Legend
- ✅ Done
- 🔧 Partially built — needs extension
- ⬜ Not started

---

## Current State Summary

The following infrastructure already exists:
- Auth (Passport, register/login/OTP/social)
- EOI basic CRUD (`eois`, `eoi_details` tables + controller)
- Points calculator (submit, breakdown, suggestions)
- Invitation rounds (read + admin sync)
- Onboarding Q&A
- Reference tables (countries, states, visa_subclasses, occupation_lists)

---

## Module 1: User EOI

**Status:** ✅ Done

### What exists
- `eois` table: `user_id`, `eoi_number`, `submission_date`, `total_points`, `subclass_id`, `occupation_id`, `eoi_status`, `total_points_assessed`
- `EoiSubmissionController`: GET (index/show), POST, PUT — no DELETE
- `EoiResource`, `StoreEoiRequest`, `UpdateEoiRequest`

### What's missing

| Field | Action |
|---|---|
| `expiry_date` | Add to migration + model + requests |
| `state_nomination` | Add to migration + model + requests |
| `notes` | Add to migration + model + requests |
| `anzsco_code` | Add to migration + model (or derive from `occupation_id`) |
| `current_status` | Rename/alias `eoi_status` or add separate field |
| DELETE /api/v1/eois/{id} | Add `destroy` to controller + route |

### Steps

1. **Migration** — `add_missing_fields_to_eois_table`
   - `expiry_date` (date, nullable)
   - `state_nomination` (string, nullable)
   - `notes` (text, nullable)

2. **Route** — add `DELETE /api/v1/eois/{id}` mapped to `EoiSubmissionController::destroy`

3. **Controller** — add `destroy` method with policy check, soft delete

4. **Form Requests** — update `StoreEoiRequest` and `UpdateEoiRequest` with new fields; add `submission_date < expiry_date` validation rule

5. **Resource** — update `EoiResource` to expose new fields

6. **Model** — add `expiry_date`, `state_nomination`, `notes` to `$fillable`; add `SoftDeletes`

7. **Tests** — `EoiCrudTest`: full CRUD happy/failure paths, date validation, ownership check

---

## Module 2: EOI Timeline / Update History

**Status:** ✅ Done

Record every field change made to an EOI.

### Steps

1. **Migration** — `create_eoi_histories_table`
   - `id`, `eoi_id` (FK), `change_type` (string), `previous_value` (json), `updated_value` (json), `notes` (text, nullable), `changed_at` (timestamp)

2. **Model** — `EoiHistory` with `BelongsTo Eoi`; add `HasMany EoiHistory` on `Eoi`

3. **Service** — `EoiHistoryService::record(Eoi $eoi, array $previousData)` — diff old vs. new, persist one record per update

4. **Hook into EoiSubmissionController::update** — call `EoiHistoryService::record` inside the existing `DB::transaction` after `$eoi->update()`

5. **Controller method** — `GET /api/v1/eois/{id}/history` → `EoiHistoryController::index`

6. **Resource** — `EoiHistoryResource`

7. **Route** — add inside `auth:api` group: `GET /eois/{id}/history`

8. **Tests** — history is created on update, empty history returns empty array, unauthorized user cannot read

---

## Module 3: Document Tracker

**Status:** ✅ Done

### Steps

1. **Migration** — `create_documents_table`
   - `id`, `user_id` (FK), `eoi_id` (FK, nullable), `document_type` (enum: passport, english_test, skills_assessment, employment_letter, educational_certificate, state_nomination, other), `document_name` (string), `expiry_date` (date, nullable), `issue_date` (date, nullable), `attachment_url` (string, nullable), `reminder_days` (integer, default 30), `notes` (text, nullable), timestamps, soft deletes

2. **Enum** — `App\Enums\DocumentType`

3. **Model** — `Document` with `BelongsTo User`, `BelongsTo Eoi`, `SoftDeletes`

4. **Form Requests** — `StoreDocumentRequest`, `UpdateDocumentRequest`

5. **Resource** — `DocumentResource`

6. **Controller** — `DocumentController` (V1)
   - `index` — GET /api/v1/documents (user's documents, paginated)
   - `store` — POST /api/v1/documents
   - `update` — PUT /api/v1/documents/{id}
   - `destroy` — DELETE /api/v1/documents/{id}
   - `expiring` — GET /api/v1/documents/expiring (within `reminder_days` of expiry)

7. **Policy** — `DocumentPolicy` (user owns document)

8. **Routes** — add inside `auth:api` group

9. **Tests** — CRUD, expiring filter, ownership enforcement

---

## Module 4: Reminder Engine

**Status:** ✅ Done

### Steps

1. **Migration** — `create_reminders_table`
   - `id`, `user_id` (FK), `reminder_type` (enum: eoi_expiry, passport_expiry, english_test_expiry, skills_assessment_expiry, state_nomination_expiry, document_expiry, custom), `reference_id` (unsignedBigInteger, nullable), `reminder_date` (date), `status` (enum: pending, sent, dismissed, default pending), `sent_at` (timestamp, nullable), timestamps

2. **Enum** — `App\Enums\ReminderType`, `App\Enums\ReminderStatus`

3. **Model** — `Reminder` with `BelongsTo User`

4. **Service** — `ReminderGenerationService`
   - `generateForEoi(Eoi $eoi)` — creates reminder from `expiry_date - reminder_days`
   - `generateForDocument(Document $document)` — creates reminder from `expiry_date - reminder_days`
   - Called after EOI create/update and Document create/update

5. **Jobs**
   - `GenerateDailyReminders` — scans EOIs and Documents expiring soon, upserts Reminder records
   - `SendPushNotifications` — sends push for due reminders
   - `SendEmailNotifications` — sends email for due reminders

6. **Scheduler** — register jobs in `routes/console.php`:
   - `GenerateDailyReminders` → daily
   - `SendPushNotifications` → daily
   - `SendEmailNotifications` → daily

7. **Controller** — `ReminderController` (V1)
   - `index` — GET /api/v1/reminders
   - `markRead` — PATCH /api/v1/reminders/{id}/read

8. **Resource** — `ReminderResource`

9. **Routes** — add inside `auth:api` group

10. **Tests** — reminder created on EOI/Document save, mark-read, generation job creates correct reminders

---

## Module 7: Notification Module

**Status:** ✅ Done

### Steps

1. **Migration** — `create_notifications_table`
   - `id`, `user_id` (FK), `type` (enum: new_invitation_round, occupation_update, document_expiry, eoi_expiry, reminder), `title` (string), `message` (text), `is_read` (boolean, default false), `metadata` (json, nullable), `created_at`, `updated_at`

2. **Enum** — `App\Enums\NotificationType`

3. **Model** — `UserNotification` (avoid collision with Laravel's `Notification`) with `BelongsTo User`

4. **Service** — `NotificationService::create(User $user, NotificationType $type, string $title, string $message, array $metadata = [])`

5. **Controller** — `NotificationController` (V1)
   - `index` — GET /api/v1/notifications (paginated, ordered by created_at desc)
   - `markRead` — PATCH /api/v1/notifications/{id}/read
   - `markAllRead` — PATCH /api/v1/notifications/read-all

6. **Resource** — `NotificationResource`

7. **Routes** — add inside `auth:api` group

8. **Tests** — list, mark one read, mark all read, cannot read other user's notifications

---

## Module 8: Automatic Matching Logic

**Status:** ✅ Done

Triggered whenever a new invitation round is synced.

### Steps

1. **Job** — `MatchEoisWithInvitationRound(InvitationRound $round)`
   - Query all users with active EOIs matching the round's `visa_subclass`
   - For occupation-specific rounds, also filter by `occupation_id`
   - For each match, evaluate:
     - User points >= round's minimum points → notify "You may have been eligible for this round"
     - User points within configurable threshold (e.g. `APP_INVITATION_THRESHOLD_POINTS=5`) below minimum → notify "You were close to this round's cutoff"
   - Dispatch `NotificationService::create` for each matched user

2. **Config** — add `invitation.threshold_points` to `config/invitation.php` (env: `INVITATION_THRESHOLD_POINTS`, default 5)

3. **Hook** — dispatch `MatchEoisWithInvitationRound` from wherever invitation rounds are synced/created (check `InvitationRoundController` or seeder)

4. **Tests** — exact match triggers notification, close-match triggers notification, no match creates no notification, occupation filter works

---

## Module 9: Security Hardening

**Status:** ✅ Done

### Remaining items

| Item | Action |
|---|---|
| Authorization policies | Add `EoiPolicy`, `DocumentPolicy`, `ReminderPolicy` — register in `AppServiceProvider` |
| Soft deletes | Add `SoftDeletes` to `Eoi`, `Document` |
| Pagination | Enforce on all `index` endpoints (default page size 15) |
| Audit logging | Use `EoiHistory` for EOI changes; add activity log service for sensitive actions |
| File upload security | Validate MIME types on `attachment_url`; store via `Storage::disk('s3')` or local with signed URLs |
| Sensitive data encryption | Encrypt `attachment_url` column if documents are sensitive |

---

## Implementation Order

| Priority | Module | Reason |
|---|---|---|
| 1 | Module 1 — EOI completion | Foundation all other modules depend on |
| 2 | Module 7 — Notifications | Required by Module 8 matching |
| 3 | Module 2 — EOI History | Low complexity, high value |
| 4 | Module 3 — Documents | Independent, unblocked |
| 5 | Module 8 — Matching Logic | Depends on notifications |
| 6 | Module 4 — Reminders | Depends on documents + notifications |
| 7 | Module 9 — Security | Cross-cutting, finalize last |

---

## File Checklist (new files to create)

```
app/
  Enums/
    DocumentType.php
    ReminderType.php
    ReminderStatus.php
    NotificationType.php
  Models/
    EoiHistory.php
    Document.php
    Reminder.php
    UserNotification.php
  Http/
    Controllers/V1/
      EoiHistoryController.php
      DocumentController.php
      ReminderController.php
      NotificationController.php
    Requests/
      StoreDocumentRequest.php
      UpdateDocumentRequest.php
    Resources/
      EoiHistoryResource.php
      DocumentResource.php
      ReminderResource.php
      NotificationResource.php
    Policies/
      EoiPolicy.php
      DocumentPolicy.php
      ReminderPolicy.php
  Services/
    EoiHistoryService.php
    ReminderGenerationService.php
    NotificationService.php
  Jobs/
    GenerateDailyReminders.php
    SendPushNotifications.php
    SendEmailNotifications.php
    MatchEoisWithInvitationRound.php

database/migrations/
  *_add_missing_fields_to_eois_table.php
  *_create_eoi_histories_table.php
  *_create_documents_table.php
  *_create_reminders_table.php
  *_create_notifications_table.php

database/factories/
  EoiHistoryFactory.php
  DocumentFactory.php
  ReminderFactory.php
  UserNotificationFactory.php

tests/Feature/
  EoiCrudTest.php
  EoiHistoryTest.php
  DocumentTest.php
  ReminderTest.php
  NotificationTest.php
  InvitationMatchingTest.php

config/
  invitation.php
```
