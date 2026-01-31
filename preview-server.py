import http.server
import socketserver
import os
import webbrowser
import time
from threading import Timer

# Change to the WordPress directory
os.chdir(r'c:\Users\Danie\projects\inner-flow')

PORT = 8080

class WordPressHandler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        # Redirect root to wp-admin/install.php
        if self.path == '/' or self.path == '':
            self.send_response(302)
            self.send_header('Location', '/SETUP.html')
            self.end_headers()
            return
        return super().do_GET()

def open_browser():
    webbrowser.open(f'http://localhost:{PORT}')

print("=" * 60)
print("  🏔️  INNER FLOW - PREVIEW SERVER")
print("=" * 60)
print()
print("NOTE: This is a PREVIEW server showing project files.")
print("To run the actual WordPress site, you need PHP + MySQL.")
print()
print("Server starting at: http://localhost:8080")
print()
print("RECOMMENDED SETUP OPTIONS:")
print("  1. Local by Flywheel: https://localwp.com/")
print("  2. XAMPP: https://www.apachefriends.org/")
print("  3. Docker: docker-compose up -d")
print()
print("Press Ctrl+C to stop the server")
print("=" * 60)
print()

# Open browser after 2 seconds
Timer(2.0, open_browser).start()

# Start the server
with socketserver.TCPServer(("", PORT), WordPressHandler) as httpd:
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nServer stopped.")
