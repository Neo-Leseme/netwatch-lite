NetWatch Lite
 Simple Network Status Dashboard for Small Teams

Version: 1.0
Date:20 March 2026
Author: Neo Leseme

 1. Project Overview

 1.1 Purpose
NetWatch Lite is a full-stack web application designed to monitor server uptime and response times in real-time. It provides small IT teams, students, and system administrators with a simple, visual dashboard to track network health without complex enterprise tools.

 1.2 Key Features
   User Authentication: Secure login and registration system with password hashing (bcrypt).
   Live Dashboard: Real-time server status cards that auto-refresh every 10 seconds.
   Response Time Charts: Interactive line charts visualizing ping history using Chart.js.
   Background Monitoring: Python-based service that pings servers every 60 seconds.
   CSV Export: Functionality to download monitoring logs for offline analysis.
   Server Management: Add and manage servers directly via the web UI.
   Modern Interface: Clean, responsive dark-themed user interface.

 1.3 Target Audience
   Students learning networking and web development
   Small IT teams requiring basic uptime monitoring
   System administrators managing homelabs or small infrastructure
   Developers building portfolio projects demonstrating full-stack skills

 2. Technical Architecture

 2.1 Technology Stack

| Component | Technology | Purpose |
| : | : | : |
| Frontend | HTML5, CSS3, Vanilla JavaScript, Chart.js | User interface and data visualization |
| Backend | PHP 8+, PDO (Prepared Statements) | API endpoints, authentication, business logic |
| Database | MySQL (via XAMPP) | Persistent storage for users, servers, and logs |
| Monitoring | Python 3.10+, ping3, mysql-connector-python | Background ICMP ping service |
| Web Server | Apache (XAMPP) | Serves PHP application and static assets |
| OS Support | Windows 10/11, Ubuntu Linux 22.04+ | Cross-platform development and deployment |

 2.2 System Architecture Diagram

```
[User Browser]
       |
       | HTTP/HTTPS
       v
[Apache Web Server]
       |
       | PHP Execution
       v
[PHP Backend Layer]
   |           |
   | Auth      | Data API
   v           v
[MySQL Database] <--> [Python Monitoring Service]
   ^                           |
   |                           | ICMP Ping
   |                           v
   +-- [Target Servers]
```

 2.3 Database Schema

Users Table
   `id` (INT, Primary Key, Auto Increment)
   `username` (VARCHAR 50, Unique)
   `email` (VARCHAR 100, Unique)
   `password_hash` (VARCHAR 255)
   `created_at` (TIMESTAMP)

Servers Table
   `id` (INT, Primary Key, Auto Increment)
   `server_name` (VARCHAR 100)
   `ip_address` (VARCHAR 45)
   `created_by` (INT, Foreign Key to Users.id)
   `is_active` (TINYINT, Default 1)

StatusLogs Table
   `id` (INT, Primary Key, Auto Increment)
   `server_id` (INT, Foreign Key to Servers.id)
   `status` (ENUM: 'online', 'offline')
   `response_time` (INT, milliseconds)
   `timestamp` (TIMESTAMP)



 3. Installation Guide

 3.1 Prerequisites

For Windows Users:
   XAMPP for Windows (includes Apache, MySQL, PHP)
   Python 3.10 or higher (from python.org)
   Git for Windows (optional, for version control)

For Linux (Ubuntu) Users:
   XAMPP for Linux (`/opt/lampp`)
   Python 3.10+ (`sudo apt install python3 python3-pip python3-venv`)
   Build tools (`sudo apt install build-essential libssl-dev`)

 3.2 Installation Steps (Windows)

Step 1: Deploy Project Files
1.  Download or clone the project repository.
2.  Copy the entire `netwatch-lite` folder to your XAMPP web root:
    `C:\xampp\htdocs\netwatch-lite`

Step 2: Start XAMPP Services
1.  Open the XAMPP Control Panel.
2.  Click Start next to Apache and MySQL.
3.  Verify both modules show "Running" in green.

Step 3: Configure Database
1.  Open a web browser and navigate to `http://localhost/phpmyadmin`.
2.  Click New in the left sidebar.
3.  Enter database name: `netwatch_lite` and click Create.
4.  Select the new database, click the SQL tab.
5.  Click Import, browse to `database/schema.sql`, and click Go.
       Alternative: Copy and paste the SQL from `schema.sql` into the SQL tab and click Go.

Step 4: Install Python Dependencies
1.  Open Command Prompt (cmd) or PowerShell.
2.  Navigate to the project directory:
    ```cmd
    cd C:\xampp\htdocs\netwatch-lite
    ```
3.  Install required Python packages:
    ```cmd
    pip install mysql-connector-python ping3
    ```

Step 5: Start the Monitoring Service
1.  In the same command prompt, run the Python script:
    ```cmd
    python scripts\ping_monitor.py
    ```
2.  You should see output indicating the monitor has started:
    ```
    NetWatch Monitor Started...
    [OK] Server 1 (8.8.8.8) - 28ms
    ```
3.  Important: Keep this command window open. Closing it will stop the monitoring service.

Step 6: Access the Application
1.  Open a web browser.
2.  Navigate to: `http://localhost/netwatch-lite/frontend/login.html`
3.  Click Register to create a new account.
4.  After registration, log in with your credentials.
5.  You will be redirected to the dashboard.

 3.3 Installation Steps (Ubuntu Linux)

Step 1: Deploy Project Files
```bash
sudo cp -r /path/to/netwatch-lite /opt/lampp/htdocs/
sudo chown -R $USER:$USER /opt/lampp/htdocs/netwatch-lite
```

Step 2: Start XAMPP
```bash
sudo /opt/lampp/lampp start
 Verify status:
sudo /opt/lampp/lampp status
```

Step 3: Configure Database
   Follow the same database steps as in the Windows guide (Section 3.2, Step 3) using `http://localhost/phpmyadmin`.

Step 4: Setup Python Virtual Environment
```bash
cd /opt/lampp/htdocs/netwatch-lite
python3 -m venv venv
source venv/bin/activate
pip install mysql-connector-python ping3
```

Step 5: Grant Ping Permissions (Linux Specific)
Linux requires special permissions for raw socket access (ping). Choose one option:

Option A: Set Capability (Recommended)
```bash
 Find the real Python binary path:
ls -la venv/bin/python
 Example output: python -> /usr/bin/python3.12

 Grant capability to the real binary:
sudo setcap cap_net_raw+ep /usr/bin/python3.12
```

Option B: Run with Sudo (Simpler for Testing)
```bash
 Run the script with elevated privileges:
sudo /opt/lampp/htdocs/netwatch-lite/venv/bin/python scripts/ping_monitor.py
```

Step 6: Start Monitoring and Access App
```bash
 Start monitor (use sudo if you chose Option B above)
python scripts/ping_monitor.py

 Access in browser:
 http://localhost/netwatch-lite/frontend/login.html
```



 4. Project Structure

```
netwatch-lite/
|
+-- backend/                   PHP API and Logic
|   +-- auth.php              User registration and login handling
|   +-- db.php                Database connection (PDO)
|   +-- api_get_status.php    Endpoint for dashboard status cards
|   +-- api_get_chart_data.php  Endpoint for Chart.js data
|   +-- export_csv.php        Handler for CSV file download
|   +-- add_server.php        Endpoint for adding new servers
|
+-- frontend/                  User Interface Files
|   +-- login.html            User login page
|   +-- register.html         User registration page
|   +-- dashboard.html        Main dashboard with charts and cards
|
+-- scripts/                   Background Services
|   +-- ping_monitor.py       Python script for periodic server pinging
|
+-- database/                  Database Configuration
|   +-- schema.sql            SQL file to create tables
|
+-- docs/                      Documentation Assets
|   +-- architecture.md       Detailed architecture notes
|   +-- [screenshots]         Images for documentation
|                     Python Virtual Environment (GitIgnored)
+-- README.md                  This document
+-- .gitignore                 Git ignore rules
```



 5. Usage Guide

 5.1 User Registration and Login
1.  Navigate to the registration page.
2.  Enter a unique username, valid email, and secure password.
3.  Click Register. You will be redirected to the login page.
4.  Enter your credentials and click Login.

 5.2 Adding a Server to Monitor
1.  On the dashboard, locate the "Add New Server" section.
2.  Enter a descriptive name (e.g., "Google DNS").
3.  Enter the IP address or domain (e.g., `8.8.8.8` or `google.com`).
4.  Click Add Server.
5.  The new server will appear in the dashboard after the next monitoring cycle (approx. 60 seconds).

 5.3 Interpreting the Dashboard
   Status Cards:
       Green Border/Background: Server is online and responding.
       Red Border/Background: Server is offline or unreachable.
       Response Time: Displayed in milliseconds (ms). Lower is better.
   Response Time Chart:
       Shows the last 10 successful ping responses.
       X-axis: Time of check (HH:MM format).
       Y-axis: Response time in milliseconds.
       Updates automatically every 30 seconds.
   Auto-Refresh:
       Server status cards update every 10 seconds.
       The chart updates every 30 seconds.
       No manual page refresh is required.

 5.4 Exporting Data to CSV
1.  Click the Export CSV button on the dashboard.
2.  A file named `netwatch_logs_YYYY-MM-DD_HH-MM-SS.csv` will download.
3.  Open the file in Microsoft Excel, LibreOffice Calc, or any text editor.
4.  The file contains columns: Timestamp, Server Name, IP Address, Status, Response Time (ms).

 5.5 Managing the Monitoring Service
   To Stop: Press `Ctrl + C` in the terminal window running `ping_monitor.py`.
   To Restart: Run the command from Step 5 of the installation guide.
   Logs: The terminal window displays real-time ping results. For persistent logging, modify the Python script to write to a file.



 6. Testing Checklist

Use this checklist to verify all components are functioning correctly.

Authentication
- [ ] Can register a new user with unique username/email
- [ ] Registration fails with duplicate username/email
- [ ] Can login with valid credentials
- [ ] Login fails with invalid credentials
- [ ] Password is hashed in database (not plain text)

Server Management
- [ ] Can add a new server via the UI form
- [ ] New server appears in `Servers` database table
- [ ] Added server appears on dashboard after 60 seconds
- [ ] Invalid IP addresses are handled gracefully

Monitoring Service
- [ ] Python script starts without errors
- [ ] Terminal shows `[OK]` for reachable servers
- [ ] Terminal shows `[FAIL]` for unreachable servers
- [ ] `StatusLogs` table receives new entries every 60 seconds
- [ ] Response times are recorded as positive integers

Dashboard
- [ ] Status cards display correct server name and IP
- [ ] Online servers show green styling
- [ ] Offline servers show red styling
- [ ] Response times update in real-time
- [ ] Chart renders with data points
- [ ] Chart updates automatically without page reload

Export Functionality
- [ ] CSV file downloads when button is clicked
- [ ] CSV file contains header row
- [ ] CSV data matches database records
- [ ] File opens correctly in spreadsheet software



 7. Troubleshooting

 7.1 Common Issues

Issue: HTTP 500 Internal Server Error
   Cause: PHP syntax error or database connection failure.
   Solution:
    1.  Enable error display in `backend/db.php` by adding:
        ```php
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ```
    2.  Check the Apache error log: `C:\xampp\apache\logs\error.log` (Windows) or `/opt/lampp/logs/error_log` (Linux).
    3.  Verify database credentials in `db.php` match your XAMPP configuration.

Issue: Python Script "Permission Denied" (Linux)
   Cause: Linux restricts raw socket access for ping.
   Solution:
       Option 1: Run with `sudo`.
       Option 2: Grant capability: `sudo setcap cap_net_raw+ep /usr/bin/python3.x`

Issue: Chart Not Displaying
   Cause: Chart.js library not loading or API endpoint error.
   Solution:
    1.  Open Browser Developer Tools (F12) > Console tab.
    2.  Check for 404 errors on `chart.js` URL.
    3.  Verify `api_get_chart_data.php` returns valid JSON by visiting it directly in browser.

Issue: CSV Download Fails / Unauthorized
   Cause: Session not active or file permissions.
   Solution:
    1.  Ensure you are logged into the dashboard before clicking export.
    2.  Check that `export_csv.php` has `session_start()` at the top.
    3.  Verify file permissions allow PHP to read the script.

Issue: Dashboard Shows "Loading..." Indefinitely
   Cause: JavaScript fetch error or PHP API returning invalid JSON.
   Solution:
    1.  Check Browser Console (F12) for JavaScript errors.
    2.  Verify `api_get_status.php` is accessible and returns JSON.
    3.  Ensure the Python monitor is running and writing to `StatusLogs`.

 7.2 Debugging Tips

Enable PHP Error Reporting (Development Only)
Add to the top of any PHP file:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

Test Database Connection
Create a test file `test_db.php`:
```php
<?php
require_once 'backend/db.php';
echo "Connection successful!";
?>
```
Access via browser to verify connectivity.

Monitor Python Script Output
Keep the terminal window visible. The script prints status updates every 60 seconds. If output stops, the script may have crashed.

Check Browser Network Tab
1.  Open Developer Tools (F12) > Network tab.
2.  Refresh the dashboard.
3.  Look for failed requests (red status codes).
4.  Click on a request to see its response and headers.



 8. Security Considerations

 8.1 Implemented Security Measures
   Password Hashing: All passwords are hashed using `password_hash()` with the bcrypt algorithm before storage.
   Prepared Statements: All database queries use PDO prepared statements to prevent SQL injection attacks.
   Session Management: User authentication state is maintained via server-side PHP sessions.
   Input Validation: Form inputs are validated server-side for required fields and basic sanitization.
   Access Control: Sensitive endpoints (CSV export, server management) verify user session before execution.

 8.2 Recommendations for Production Deployment
   HTTPS Enforcement: Configure Apache to redirect HTTP traffic to HTTPS. Obtain an SSL certificate (e.g., via Let's Encrypt).
   Environment Variables: Move database credentials out of source code into environment variables or a protected config file.
   Rate Limiting: Implement rate limiting on authentication endpoints to prevent brute-force attacks.
   CSRF Protection: Add Cross-Site Request Forgery tokens to all state-changing forms.
   Input Sanitization: Implement more robust input sanitization and output escaping to prevent XSS attacks.
   Error Handling: Disable `display_errors` in production and log errors to a secure file instead.
   File Permissions: Ensure web server user has minimal necessary permissions on project files.



 9. Future Enhancements

 9.1 Planned Features
   Alerting System: Email or SMS notifications when a server goes offline or response time exceeds a threshold.
   Multi-User Server Isolation: Allow users to manage only the servers they have added, with admin oversight.
   Advanced Analytics: Uptime percentage calculations, average response time trends, and historical reporting.
   API Documentation: OpenAPI/Swagger specification for all backend endpoints.
   Docker Support: Dockerfile and docker-compose.yml for simplified, consistent deployment across environments.
   Theme Customization: User-selectable light/dark themes with preference persistence.

 9.2 Scalability Improvements
   Queue System: Replace direct Python-MySQL communication with a message queue (e.g., Redis) for better decoupling.
   Caching Layer: Implement Redis or Memcached for frequently accessed dashboard data.
   Horizontal Scaling: Design backend to support multiple monitoring worker instances.
   Database Optimization: Add indexes to `StatusLogs.timestamp` and foreign keys for faster queries on large datasets.



 10. Screenshot
Figure 1: Login Page
<img width="1366" height="739" alt="Screenshot from 2026-03-20 23-04-45" src="https://github.com/user-attachments/assets/c99bba79-5ab7-4e53-8b5c-04e71793b7ab" />



Figure 2: Dashboard Overview
<img width="1366" height="739" alt="Screenshot from 2026-03-20 21-56-22" src="https://github.com/user-attachments/assets/e46ed7b6-5c90-41fc-9483-90e75e0a9934" />



Figure 3: Add Server Form
<img width="1366" height="739" alt="Screenshot from 2026-03-20 23-06-12" src="https://github.com/user-attachments/assets/8305d8c5-0a5f-405e-abc2-c9483207bf43" />



Figure 4: CSV Export Example

<img width="1366" height="739" alt="Screenshot from 2026-03-20 22-20-10" src="https://github.com/user-attachments/assets/303d3e96-8875-4e11-a83f-e2b3150629b6" />
<img width="1366" height="739" alt="Screenshot from 2026-03-20 22-27-26" src="https://github.com/user-attachments/assets/7eccd091-aa4b-4804-aa85-5a467b36acdd" />

Figure 5: Response Time Chart Detail
<img width="1366" height="739" alt="Screenshot from 2026-03-20 22-21-32" src="https://github.com/user-attachments/assets/a4728bf2-1d00-4884-92a2-d703e10e52f5" />

 11. License

MIT License

Copyright (c) 2026 Neo Leseme

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.



 12. Contact and Support

Project Author: Neo Leseme

Repository: https://github.com/Neo-Leseme/Netwatch-lite

Issues and Bug Reports: Please use the GitHub Issues tab for bug reports and feature requests.

Contributions: Contributions are welcome. Please fork the repository and submit a pull request with a clear description of your changes.

Document Version: 1.0
Last Updated:20 March 2026
Generated for NetWatch Lite Project
