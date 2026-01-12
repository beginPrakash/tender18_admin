Elasticsearch + PHP integration (Core PHP)
========================================

Files included:
- elastic_client.php      : ES helper using curl (set $ES_HOST at top)
- create_index.php       : One-time index + mapping creation
- index_sync.php         : Function sync_tender_by_id($id) to index a single tender (include and call after insert/update)
- bulk_index.php         : CLI script to bulk-index existing tenders in batches
- search_tenders.php     : Example search endpoint (GET q, from, to, size)
- README.txt             : This file

Setup:
1. Upload files to your project directory (e.g., /home/user/public_html/es/)
2. Edit elastic_client.php if ES host is different.
3. Run create_index.php once: php create_index.php
4. To index existing data, run: php bulk_index.php
5. Add sync call in your insert/update logic:
   include 'index_sync.php';
   $res = sync_tender_by_id($newId); // $newId is 'id' in tenders_all

Security:
- The scripts contain DB credentials; move them to a secure config or environment variables for production.
- Restrict access to these PHP files (do not expose create_index.php/bulk_index.php via public web).

Notes:
- The code uses simple curl-based ES client to avoid composer dependency and to work on PHP 7.3.
- For high-volume production, consider using the official elasticsearch-php client via composer and background workers (queues) for sync.
- Adjust batch size in bulk_index.php if you encounter memory/time issues.
