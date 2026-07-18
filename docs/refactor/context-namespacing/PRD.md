# PRD: Context-Driven Namespacing Refactor

**ID:** LOCAL-001

## Execution Rules (The Loop Contract)

1. **Always update TRACKER.md before and after touching a module.**
2. **Migrate by module, not by file.** Move all files in a module that share the same context together in one batch.
3. **After each module:**
   - Run `composer dump-autoload`
   - Run `php -l` on all moved files
   - Run `composer test` (full suite)
4. **If a migration fails** (broken namespace, missing import, PHP syntax error):
   - STOP immediately
   - Log the exact error in TRACKER.md under "Blockers / Errors"
   - Revert the module's files and ask for human input
5. **Route files** are updated by the migration script (they contain `use` imports). No manual route edits needed.
6. **No context splitting within a file.** If a Handler is used by both Client and Barber contexts, it goes to `Shared/`.
7. **Exceptions stay with their Resolver.** `ChairNotAvailableException` stays in the same context as `CreateBookingEloquentResolver`.
