# Sanitized WordPress restore kit

This directory contains the reviewed package used to restore the public ChinaWorthSeeing demo to InfinityFree on 2026-09-16.

- `packages/cws-restore.zip`: temporary administrator-only restore plugin.
- `packages/cws-files-01.zip` through `cws-files-12.zip`: checksum-locked WordPress content packs.
- `SHA256SUMS.txt`: package hashes.
- `tool-source/`: source of the temporary restore plugin.
- `build_restore.py`: reproducibility record for how the sanitized payload was produced. It expects the original local source paths and is not a one-command installer on a fresh computer.

The payload contains 23 published pages, themes, required plugins, media, navigation, slider and the enquiry form definition. It excludes users, sessions, enquiries, comments, database credentials, SMTP/Turnstile credentials and host-specific configuration. Form email notification is disabled in this package.

After restoration, deactivate and delete the temporary plugin. Configure email and Turnstile separately and run an end-to-end enquiry test before treating the site as operational.
