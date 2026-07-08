# Backend API Specification Prompt – EOI Tracking Module (Without SkillSelect Integration)

## Project Context

I am building the backend APIs for a mobile application that helps Australian migration applicants manage and track their SkillSelect Expression of Interest (EOI).

SkillSelect does not provide an official API for accessing user EOI information, and the application must not log into or scrape a user's SkillSelect account. Instead, users will manually maintain their EOI information within the app, while the backend automatically syncs publicly available SkillSelect invitation round data.

The backend should be designed as a scalable REST API using best practices, proper authentication, validation, and clean architecture.

---

## Objective

Design and implement a complete backend API for an EOI Tracking module.

The backend should support:

* Manual EOI management
* Invitation round comparison
* Reminder management
* Document tracking
* EOI history
* Notification generation

---

# Module 1: User EOI

Each user can have one or more EOIs.

Fields:

* id
* user_id
* eoi_reference_number
* visa_subclass
* occupation_name
* anzsco_code
* total_points
* submission_date
* expiry_date
* state_nomination
* current_status
* notes
* created_at
* updated_at

Required APIs

POST /api/eois

Create a new EOI.

GET /api/eois

Return all EOIs for the authenticated user.

GET /api/eois/{id}

Return a single EOI.

PUT /api/eois/{id}

Update an EOI.

DELETE /api/eois/{id}

Delete an EOI.

Validation

* submission_date < expiry_date
* total_points must be numeric
* authenticated user only

---

# Module 2: EOI Timeline / Update History

Maintain a complete history whenever an EOI is updated.

Fields

* id
* eoi_id
* change_type
* previous_value (JSON)
* updated_value (JSON)
* notes
* changed_at

APIs

GET /api/eois/{id}/history

Return update history.

---

# Module 3: Document Tracker

Users can manage migration-related documents.

Document Types

* Passport
* English Test
* Skills Assessment
* Employment Letter
* Educational Certificate
* State Nomination
* Other

Fields

* id
* user_id
* eoi_id
* document_type
* document_name
* expiry_date
* issue_date
* attachment_url
* reminder_days
* notes

APIs

POST /api/documents

GET /api/documents

PUT /api/documents/{id}

DELETE /api/documents/{id}

GET /api/documents/expiring

Return documents nearing expiry.

---

# Module 4: Reminder Engine

Generate reminders for:

* EOI expiry
* Passport expiry
* English test expiry
* Skills assessment expiry
* State nomination expiry
* Custom reminders

Fields

* id
* user_id
* reminder_type
* reference_id
* reminder_date
* status
* sent_at

Cron Jobs

Daily reminder generation

Push notification scheduler

Email notification scheduler

APIs

GET /api/reminders

PATCH /api/reminders/{id}/read


# Module 7: Notification Module

Store notifications.

Types

* New Invitation Round
* Occupation Update
* Document Expiry
* EOI Expiry
* Reminder

Fields

* id
* user_id
* type
* title
* message
* is_read
* metadata (JSON)
* created_at

APIs

GET /api/notifications

PATCH /api/notifications/{id}/read

PATCH /api/notifications/read-all

---

# Module 8: Automatic Matching Logic

Whenever a new invitation round is synchronized:

Compare every user's active EOIs with the latest invitation round.

Generate notifications when:

* Same visa subclass
* Same occupation (if occupation-specific)
* User points are greater than or equal to invitation points
* User points are close to the invitation threshold (configurable, e.g. within 5 points)

Do not attempt to predict invitations—only compare user-entered information with published public data.

---

# Module 9: Security

* JWT/Sanctum authentication
* Authorization policies
* Request validation
* API Resources
* Pagination
* Rate limiting
* Soft deletes where appropriate
* Audit logging
* File upload security
* Encrypt sensitive data if required

---

# Expected Output

Generate:

* Database schema
* Laravel migrations
* Eloquent models
* Relationships
* Form Requests
* API Resources
* Controllers
* Service classes
* Repository layer (if applicable)
* REST API endpoints
* Validation rules
* Background jobs
* Scheduler configuration
* Notification workflow
* Error handling
* OpenAPI/Swagger documentation

Follow Laravel 12 best practices, use clean architecture principles, and produce production-ready backend APIs that can be consumed by Android and iOS applications.
