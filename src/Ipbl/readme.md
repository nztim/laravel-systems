# IPBL

Stores information related to bad requests in the database and uploads to central server for blocklist generation.

### Installation

* Register `IpblServiceProvider::class`
* Configure name of database connection as `database.ipbl`. Typically SQLite.
* Run `ipbl:migration` to add ipbl table.
* Configure IPBL submission with `services.ipbl.url` and `services.ipbl.key`.
* Add daily scheduler entry for `IpblUploadCommand::class` - expires old entries and uploads list to server.

### Usage

* Use `Ipbl::class` methods in places that handle bad requests. For example:
* Add `Ipbl::evaluate404()` to your 404 handler, add IPs that scan for .env files.
* Use `Ipbl::add()` in places that handle bad form requests, e.g. triggering honeypot, Turnstile, etc.
* Choose a severity depending on the request, an IP reaching 100 points is blocked.
* View full list with `ipbl:show` command.
* Application blocklist is uploaded to central server.
* Cron script installs combined blocklist as `/etc/apache/ipbl.conf`.
