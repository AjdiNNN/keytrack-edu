from pynput import keyboard
from pynput import mouse
from datetime import datetime
import psutil
import platform
import pygetwindow as gw

def is_vscode_running():
    if platform.system() == "Windows":
        for proc in psutil.process_iter(['pid', 'name']):
            if "Code.exe" == proc.info['name']:
                return True
    elif platform.system() == "Linux":
        for proc in psutil.process_iter(['pid', 'name']):
            if "code" == proc.info['name']:
                return True
    return False


def on_press(key):
    if is_vscode_running():
        try:
            active_window = gw.getActiveWindow()
            if active_window is not None and "Visual Studio Code" in active_window.title:
                try:
                    print(f'{datetime.now()} alphanumeric key {key.char} pressed')
                except AttributeError:
                    print(f'{datetime.now()} special key {key} pressed')
        except gw.PyGetWindowException:
            pass


def on_click(x, y, button, pressed):
     if is_vscode_running():
        try:
            active_window = gw.getActiveWindow()
            if active_window is not None and "Visual Studio Code" in active_window.title:
                    print('{0} at {1}  Button: {2}'.format('Pressed' if pressed else 'Released',(x, y), button))
        except gw.PyGetWindowException:
            pass

def on_scroll(x, y, dx, dy):
    if is_vscode_running():
        try:
            active_window = gw.getActiveWindow()
            if active_window is not None and "Visual Studio Code" in active_window.title:
                print('Scrolled {0} at {1}'.format(
                    'down' if dy < 0 else 'up',
                    (x, y)))
        except gw.PyGetWindowException:
            pass


# ...or, in a non-blocking fashion:
keyboard_listener = keyboard.Listener(
    on_press=on_press)

# ...or, in a non-blocking fashion:
mouse_listener = mouse.Listener(
    on_click=on_click,
    on_scroll=on_scroll)


keyboard_listener.start()
mouse_listener.start()
keyboard_listener.join()
mouse_listener.join()
