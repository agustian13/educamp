## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

When the user types `/graphify`, invoke the `skill` tool with `skill: "graphify"` before doing anything else.

Rules:
- For codebase questions, first run `graphify query "<question>"` when graphify-out/graph.json exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- Dirty graphify-out/ files are expected after hooks or incremental updates; dirty graph files are not a reason to skip graphify. Only skip graphify if the task is about stale or incorrect graph output, or the user explicitly says not to use it.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).

## SISTER API Integration

### Working Credentials (from simatav2, verified Jun 28 2026)
- **URL**: `https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0`
- **Username**: `U8b+KVO41Gh6uZJAiR5mRw0dLsnwFio/a2Wp07W0SkZDY+z6BKKj3C4BwwAz1d1feCLjOH/uW8S52/yu3nePis1fqwv4oe4l6p2GLdhKxUk=`
- **Password**: `8y+fiWdjtcgp9/twZaPCcuN7I5b14y06FNv+f9PpSW8QX9c0X2kxX+ut30aML4TRFRhSrCGcIq9Wno+7+NLv5DZlsKaBDtDNVL+L351OilcVLv974MvK7YMppVT6a5oG`
- **ID Pengguna**: `954afd45-1c46-4937-a8d6-655dfc708808`
- **Production URL**: `https://sister-api.kemdiktisaintek.go.id/ws.php/1.0`

### Files
- `includes/sister-api.php` — API service class (login, 13 endpoints, encrypt/decrypt, 401 retry)
- `includes/sister-admin.php` — Admin menu + AJAX handlers (test, save, sync chunked)
- `admin-templates/sister-settings.php` — Settings UI
- `assets/js/sister-sync.js` — Chunked AJAX loop
- `assets/vendor/nprogress.css` + `nprogress.js` — Loading bar
- `assets/js/nprogress-init.js` — NProgress init

### Key Details
- Password disimpan encrypted (AES-256-CBC via openssl, fallback base64)
- Token disimpan dengan expiry 55 menit
- 401 auto-retry: token expired → refresh → retry
- Chunked sync: 10 SDM/request via AJAX, progress state di wp_options
- Dummy seed otomatis dinonaktifkan jika SISTER sudah dikonfigurasi
- WordPress live: `D:\SERVER\htdocs\campus\wp-content\themes\educamp-theme`
- Dev copy: `D:\SERVER\htdocs\educamp`
