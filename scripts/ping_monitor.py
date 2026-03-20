import mysql.connector
from ping3 import ping
import time

# DB Configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',      # Default XAMPP user
    'password': '',      # Default XAMPP password is empty
    'database': 'netwatch_lite'
}

def get_active_servers():
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, ip_address FROM Servers WHERE is_active = 1")
    servers = cursor.fetchall()
    conn.close()
    return servers

def log_status(server_id, status, response_time):
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()
    sql = "INSERT INTO StatusLogs (server_id, status, response_time) VALUES (%s, %s, %s)"
    cursor.execute(sql, (server_id, status, response_time))
    conn.commit()
    conn.close()

def main():
    print("NetWatch Monitor Started...")
    while True:
        servers = get_active_servers()
        for server in servers:
            ip = server['ip_address']
            sid = server['id']
            
            try:
                # Ping with a 2 second timeout
                delay = ping(ip, timeout=2) 
                if delay is not None:
                    # Convert seconds to milliseconds
                    ms = int(delay * 1000) 
                    log_status(sid, 'online', ms)
                    print(f"[OK] Server {sid} ({ip}) - {ms}ms")
                else:
                    log_status(sid, 'offline', 0)
                    print(f"[FAIL] Server {sid} ({ip}) - Unreachable")
            except Exception as e:
                print(f"[ERROR] {e}")
        
        # Wait 60 seconds before next check
        time.sleep(60) 

if __name__ == "__main__":
    main()