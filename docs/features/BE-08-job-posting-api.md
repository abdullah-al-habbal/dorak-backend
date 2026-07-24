# BE-08 — Job Posting API

## Status: ✅ Complete
## Frontend Consumer: JobListScreen, JobDetailScreen, ApplicationListScreen

## What Was Built
- List/Show job postings, Apply for job, List/Update applications

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/jobs` | — | `ListJobPostingsAction` |
| GET | `/api/v1/jobs/{job}` | — | `ShowJobPostingAction` |
| POST | `/api/v1/jobs/{job}/apply` | auth:barber | `ApplyForJobAction` |
| GET | `/api/v1/applications` | auth:barber | `ListApplicationsAction` |
| PUT | `/api/v1/applications/{application}/status` | auth:barber | `UpdateApplicationStatusAction` |

## Response Schemas

### GET /api/v1/jobs → 200 (paginated)
```json
{
  "data": [
    {
      "id": "uuid",
      "title": { "en": "Senior Barber", "ar": "حلاق أول" },
      "description": { "en": "...", "ar": "..." },
      "status": "open|closed",
      "branch_id": "uuid",
      "requirements": ["3+ years experience", "Fade specialist"],
      "location": "Damascus",
      "type": "full-time",
      "applications_count": 5,
      "created_at": "datetime"
    }
  ],
  "meta": { "pagination": { "..." } }
}
```

### GET /api/v1/applications → 200
```json
{
  "data": [
    {
      "id": "uuid",
      "job_posting_id": "uuid",
      "barber_id": "uuid",
      "job_posting_title": { "en": "Senior Barber", "ar": "حلاق أول" },
      "profile_snapshot": {},
      "status": "submitted|reviewed|accepted|rejected",
      "created_at": "datetime"
    }
  ]
}
```

## Tests: Contract tests
