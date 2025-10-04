# IPBL

Stores information related to bad requests in the database and uploads to central server for blocklist generation.

### Installation

* Set name of database connection as config value `database.ipbl`. Typically SQLite. 
* Register `IpblServiceProvider::class`
* Run `ipbl:migration` to add ipbl table.
* Add daily scheduler entries for `IpblDailyCommand::class` - expires old entries and writes updated list to disk.
* Default storage location is `storage_path('app/ipbl.json')`.

### Usage

* Use `Ipbl::class` methods in places that handle bad requests. For example:
* Add `Ipbl::evaluate404()` to your 404 handler, add IPs that scan for .env files.
* Use `Ipbl::add()` in places that handle bad form requests, e.g. triggering honeypot, Turnstile, etc.
* Choose a severity depending on the request, an IP reaching 100 points is blocked.
* Ipbl script gathers list, produces and installs apache blocklist.conf.
